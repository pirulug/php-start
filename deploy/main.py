import os
import stat
import paramiko
import config
import posixpath
import threading
import time

from pathlib import Path
from concurrent.futures import ThreadPoolExecutor, as_completed
from threading import Lock

# ======================
# CONFIGURACIÓN SSH
# ======================
HOST = config.FTP_HOST
PORT = config.FTP_PORT
USER = config.FTP_USER
PASSWORD = config.FTP_PASSWORD

# Aumentado ya que ahora reutilizamos conexiones (max ~10 concurrencias FTP)
MAX_WORKERS = 5 

LOCAL_ROOT = Path(__file__).resolve().parents[1]
TRASH_DIR_NAME = "_deleted"
DEPLOY_FILE = "MAINTENANCE"

IGNORED = {
    "deploy",
    ".git",
    ".doc",
    ".env",
    "node_modules",
    # "comander",
    "install",
    "db",
    "config.php",
    "LICENSE",
    "README.md",
    ".gitignore",
    "storage",
    "__pycache__",
    DEPLOY_FILE,
}


class ConnectionPool:
    """Implementa un pool de conexiones SFTP basándose en thread_local.
    Garantiza que cada hilo trabajador (worker) abra una única conexión SSH
    y la mantenga abierta para procesar múltiples archivos."""
    def __init__(self, host, port, username, password):
        self.host = host
        self.port = port
        self.username = username
        self.password = password
        self._local = threading.local()
        self.connections = []

    def get_sftp(self):
        if not hasattr(self._local, 'sftp'):
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            for _ in range(3):
                try:
                    ssh.connect(
                        self.host, self.port, self.username, self.password,
                        timeout=10, banner_timeout=15, auth_timeout=10
                    )
                    self._local.ssh = ssh
                    self._local.sftp = ssh.open_sftp()
                    self.connections.append(ssh)
                    break
                except Exception:
                    time.sleep(1)
            else:
                raise RuntimeError("No se pudo conectar por SSH para un hilo.")
                
        return self._local.sftp

    def close_all(self):
        for ssh in self.connections:
            try:
                ssh.close()
            except Exception:
                pass


class ProjectDeployer:
    """Clase principal encargada del despliegue concurrente."""
    def __init__(self):
        self.pool = ConnectionPool(HOST, PORT, USER, PASSWORD)
        self.remote_dir_cache = set()
        self.dir_lock = Lock()
        self.remote_root = None

    def find_public_html(self, sftp, base="/home"):
        for user in sftp.listdir(base):
            path = posixpath.join(base, user, "public_html")
            try:
                sftp.stat(path)
                return path
            except FileNotFoundError:
                pass
        return None

    def enable_maintenance(self):
        sftp = self.pool.get_sftp()
        maintenance_path = posixpath.join(self.remote_root, DEPLOY_FILE)
        try:
            with sftp.file(maintenance_path, "w") as f:
                f.write("deploying")
            print("✔ Modo mantenimiento ACTIVADO")
        except Exception as e:
            print(f"Error activando modo mantenimiento: {e}")

    def disable_maintenance(self):
        sftp = self.pool.get_sftp()
        maintenance_path = posixpath.join(self.remote_root, DEPLOY_FILE)
        try:
            sftp.remove(maintenance_path)
            print("✔ Modo mantenimiento DESACTIVADO")
        except FileNotFoundError:
            pass
        except Exception as e:
            print(f"Error desactivando modo mantenimiento: {e}")

    def should_ignore_local(self, path: Path) -> bool:
        relative = path.relative_to(LOCAL_ROOT).as_posix()
        if relative == ".":
            return False
            
        parts = relative.split('/')
        for ignore in IGNORED:
            if ignore in parts or path.name == ignore:
                return True
        return False

    def should_ignore_remote(self, remote_path: str) -> bool:
        rel = posixpath.relpath(remote_path, self.remote_root)
        if rel == ".":
            return False
        
        parts = rel.split('/')
        for ignore in IGNORED:
            if ignore in parts:
                return True
        return False

    def ensure_remote_dir(self, remote_path):
        with self.dir_lock:
            # Caché para no realizar stats/mkdir costosos en red
            if remote_path in self.remote_dir_cache:
                return

            parts = [p for p in remote_path.split("/") if p]
            current = ""
            if remote_path.startswith("/"):
                current = "/"
                
            sftp = self.pool.get_sftp()
            
            for part in parts:
                current = posixpath.join(current, part)
                if current in self.remote_dir_cache:
                    continue
                    
                try:
                    sftp.stat(current)
                except FileNotFoundError:
                    try:
                        sftp.mkdir(current)
                    except IOError: 
                        # Evita errores si se crean directorios sin permiso 
                        # o si el sistema virtual remoto es estricto
                        pass
                self.remote_dir_cache.add(current)

    def upload_file(self, task):
        local_file, remote_file = task
        sftp = self.pool.get_sftp()

        try:
            local_mtime = int(local_file.stat().st_mtime)
            try:
                remote_stat = sftp.stat(remote_file)
                upload = local_mtime > int(remote_stat.st_mtime)
            except FileNotFoundError:
                upload = True

            if upload:
                self.ensure_remote_dir(posixpath.dirname(remote_file))
                sftp.put(str(local_file), remote_file)
                return f"Subido: {remote_file}"

            return f"OK: {remote_file}"
        except Exception as e:
            return f"Error subiendo {remote_file}: {e}"

    def list_remote_files(self):
        sftp = self.pool.get_sftp()
        files = set()

        def walk(path):
            try:
                for item in sftp.listdir_attr(path):
                    full = posixpath.join(path, item.filename)

                    if self.should_ignore_remote(full):
                        continue

                    if stat.S_ISDIR(item.st_mode):
                        walk(full)
                    else:
                        files.add(full)
            except IOError:
                pass 

        print(f"Explorando archivos en remoto...")
        walk(self.remote_root)
        return files

    def move_to_trash(self, remote_file):
        sftp = self.pool.get_sftp()

        try:
            rel = posixpath.relpath(remote_file, self.remote_root)
            trash_path = posixpath.join(self.remote_root, TRASH_DIR_NAME, rel)

            self.ensure_remote_dir(posixpath.dirname(trash_path))
            sftp.rename(remote_file, trash_path)
            return f"Movido a papelera: {remote_file}"
        except Exception as e:
            return f"Error moviendo a papelera {remote_file}: {e}"

    def sync_project(self):
        try:
            # El hilo principal instancia SFTP para configuraciones iniciales
            sftp = self.pool.get_sftp()
            
            self.remote_root = self.find_public_html(sftp)
            if not self.remote_root:
                raise RuntimeError("No se encontró public_html")
            
            print("Remote root:", self.remote_root)
            print("Local root :", LOCAL_ROOT)
            
            self.enable_maintenance()

            upload_tasks = []
            local_set = set()

            for root, dirs, files in os.walk(LOCAL_ROOT):
                root_path = Path(root)

                if self.should_ignore_local(root_path):
                    dirs[:] = []
                    continue

                relative = root_path.relative_to(LOCAL_ROOT).as_posix()
                remote_path = self.remote_root if relative == "." else posixpath.join(self.remote_root, relative)

                for file in files:
                    local_file = root_path / file
                    if self.should_ignore_local(local_file):
                        continue

                    remote_file = posixpath.join(remote_path, file)
                    upload_tasks.append((local_file, remote_file))
                    local_set.add(remote_file)

            remote_set = self.list_remote_files()

            print(f"Archivos locales analizados: {len(upload_tasks)}")

            # Despliegue concurrente multihilo
            with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
                for f in as_completed(executor.submit(self.upload_file, t) for t in upload_tasks):
                    print(f.result())

            to_trash = remote_set - local_set
            # Filtro adicional de seguridad
            to_trash = {p for p in to_trash if TRASH_DIR_NAME not in p.split('/')}

            if to_trash:
                print(f"Moviendo {len(to_trash)} archivos a _deleted/")
                with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
                    for f in as_completed(executor.submit(self.move_to_trash, r) for r in to_trash):
                        print(f.result())

            print("RSYNC COMPLETADO (con papelera)")

        except Exception as e:
            print(f"Error crítico en el despliegue: {e}")

        finally:
            if self.remote_root:
                self.disable_maintenance()
            # Destruye el pool de conexiones al terminar
            self.pool.close_all()

if __name__ == "__main__":
    deployer = ProjectDeployer()
    deployer.sync_project()
