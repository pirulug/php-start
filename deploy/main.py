import os
import stat
import paramiko
import config

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

MAX_WORKERS = 3

# ======================
# RUTAS
# ======================
LOCAL_ROOT = Path(__file__).resolve().parents[1]
TRASH_DIR_NAME = "_deleted"
DEPLOY_FILE = "MAINTENANCE"

# ======================
# EXCLUSIONES
# ======================
IGNORED = {
    "deploy",
    ".git",
    ".doc",
    ".env",
    "node_modules",
    "comander",
    "install",
    "db",
    "config.php",
    "LICENSE",
    "README.md",
    ".gitignore",
    "storage/logs",
    "storage/uploads",
    "__pycache__",
    DEPLOY_FILE,
}

REMOTE_DIR_CACHE = set()
DIR_LOCK = Lock()

# ======================
# SSH
# ======================
def open_ssh():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    for _ in range(3):
        try:
            ssh.connect(
                HOST, PORT, USER, PASSWORD,
                timeout=10, banner_timeout=15, auth_timeout=10
            )
            return ssh
        except Exception:
            pass
    raise RuntimeError("No se pudo conectar por SSH")

# ======================
def find_public_html(sftp, base="/home"):
    for user in sftp.listdir(base):
        path = f"{base}/{user}/public_html"
        try:
            sftp.stat(path)
            return path
        except FileNotFoundError:
            pass
    return None

# ======================
# MANTENIMIENTO
# ======================
def enable_maintenance(remote_root):
    ssh = open_ssh()
    sftp = ssh.open_sftp()
    try:
        with sftp.file(f"{remote_root}/{DEPLOY_FILE}", "w") as f:
            f.write("deploying")
        print("✔ Modo mantenimiento ACTIVADO")
    finally:
        sftp.close()
        ssh.close()


def disable_maintenance(remote_root):
    ssh = open_ssh()
    sftp = ssh.open_sftp()
    try:
        sftp.remove(f"{remote_root}/{DEPLOY_FILE}")
        print("✔ Modo mantenimiento DESACTIVADO")
    except FileNotFoundError:
        pass
    finally:
        sftp.close()
        ssh.close()

# ======================
# IGNORE
# ======================
def should_ignore_local(path: Path) -> bool:
    relative = path.relative_to(LOCAL_ROOT).as_posix()
    for ignore in IGNORED:
        if relative == ignore or relative.startswith(ignore + "/") or path.name == ignore:
            return True
    return False


def should_ignore_remote(remote_path: str, remote_root: str) -> bool:
    rel = remote_path.replace(remote_root + "/", "", 1)
    for ignore in IGNORED:
        if rel == ignore or rel.startswith(ignore + "/"):
            return True
    return False

# ======================
def ensure_remote_dir(sftp, remote_path):
    with DIR_LOCK:
        if remote_path in REMOTE_DIR_CACHE:
            return

        parts = remote_path.strip("/").split("/")
        current = ""
        for part in parts:
            current += "/" + part
            try:
                sftp.stat(current)
            except FileNotFoundError:
                try:
                    sftp.mkdir(current)
                except IOError:
                    pass
            REMOTE_DIR_CACHE.add(current)

# ======================
# UPLOAD
# ======================
def upload_file(task):
    local_file, remote_file = task
    ssh = open_ssh()
    sftp = ssh.open_sftp()

    try:
        local_mtime = int(local_file.stat().st_mtime)
        try:
            remote_stat = sftp.stat(remote_file)
            upload = local_mtime > int(remote_stat.st_mtime)
        except FileNotFoundError:
            upload = True

        if upload:
            ensure_remote_dir(sftp, os.path.dirname(remote_file))
            sftp.put(str(local_file), remote_file)
            return f"Subido: {remote_file}"

        return f"OK: {remote_file}"

    finally:
        sftp.close()
        ssh.close()

# ======================
# LISTADO REMOTO
# ======================
def list_remote_files(sftp, root):
    files = set()

    def walk(path):
        for item in sftp.listdir_attr(path):
            full = f"{path}/{item.filename}"

            if should_ignore_remote(full, root):
                continue

            if stat.S_ISDIR(item.st_mode):
                walk(full)
            else:
                files.add(full)

    walk(root)
    return files

# ======================
# PAPELERA
# ======================
def move_to_trash(remote_file, remote_root):
    ssh = open_ssh()
    sftp = ssh.open_sftp()

    try:
        rel = remote_file.replace(remote_root + "/", "", 1)
        trash_path = f"{remote_root}/{TRASH_DIR_NAME}/{rel}"

        ensure_remote_dir(sftp, os.path.dirname(trash_path))
        sftp.rename(remote_file, trash_path)
        return f"Movido a papelera: {remote_file}"

    finally:
        sftp.close()
        ssh.close()

# ======================
def sync_project():
    ssh = open_ssh()
    sftp = ssh.open_sftp()

    remote_root = find_public_html(sftp)
    if not remote_root:
        raise RuntimeError("No se encontró public_html")

    sftp.close()
    ssh.close()

    enable_maintenance(remote_root)

    try:
        print("Remote root:", remote_root)
        print("Local root :", LOCAL_ROOT)

        upload_tasks = []
        local_set = set()

        for root, dirs, files in os.walk(LOCAL_ROOT):
            root_path = Path(root)

            if should_ignore_local(root_path):
                dirs[:] = []
                continue

            relative = root_path.relative_to(LOCAL_ROOT).as_posix()
            remote_path = remote_root if relative == "." else f"{remote_root}/{relative}"

            for file in files:
                local_file = root_path / file
                if should_ignore_local(local_file):
                    continue

                remote_file = f"{remote_path}/{file}"
                upload_tasks.append((local_file, remote_file))
                local_set.add(remote_file)

        ssh = open_ssh()
        sftp = ssh.open_sftp()
        remote_set = list_remote_files(sftp, remote_root)
        sftp.close()
        ssh.close()

        print(f"Archivos locales: {len(upload_tasks)}")

        with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
            for f in as_completed(executor.submit(upload_file, t) for t in upload_tasks):
                print(f.result())

        to_trash = remote_set - local_set
        if to_trash:
            print(f"Moviendo {len(to_trash)} archivos a _deleted/")
            with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
                for f in as_completed(
                    executor.submit(move_to_trash, r, remote_root) for r in to_trash
                ):
                    print(f.result())

        print("RSYNC COMPLETADO (con papelera)")

    finally:
        disable_maintenance(remote_root)

# ======================
if __name__ == "__main__":
    sync_project()
