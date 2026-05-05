# Comander

El directorio `comander/` concentra las utilidades de línea de comandos (CLI) del framework. Estas herramientas permiten automatizar desde la generación de código hasta el mantenimiento de la integridad de los datos, asegurando un flujo de trabajo ágil y estandarizado.

A continuación se detallan los **6 comandos** disponibles en el sistema. Todos cuentan con soporte para ayuda integrada mediante los flags `--help` o `-h`.

---

## 1. Generador de Módulos (modules.php)

Es la herramienta de andamiaje (scaffolding) principal. Automatiza la creación de la estructura de carpetas, controladores de acción, vistas, enrutadores y menús según el contexto seleccionado.

**Parámetros:**
- `plural`: Nombre del módulo en plural (directorio base).
- `singular`: Nombre del recurso en singular (para rutas lógicas).
- `--context`: Contexto de la aplicación (`admin`, `home`, `api`, `ajax`).

**Ejemplos de Uso:**

```bash
# Crear un módulo administrativo completo para 'Productos'
php comander/modules.php create products product --context=admin

# Crear un endpoint de API para 'Clientes' (sin interfaz visual)
php comander/modules.php create clients client --context=api

# Crear módulo para el front-end (home) con singular deducido automáticamente
php comander/modules.php create articles --context=home
```

---

## 2. Migrador de Base de Datos (migrate.php)

Gestiona la ejecución de scripts SQL de forma ordenada. Escanea la carpeta `db/` buscando archivos que comiencen con números (ej. `01_base.sql`) para asegurar la integridad referencial.

**Lógica Interna:**
- Ordena los archivos alfanuméricamente.
- Ejecuta el contenido mediante `PDO::exec()`.

**Ejemplos de Uso:**

```bash
# Ejecutar migraciones pendientes
php comander/migrate.php

# Reiniciar base de datos (Elimina todas las tablas y re-ejecuta todo)
php comander/migrate.php fresh
```

---

## 3. Respaldo de Base de Datos (backup.php)

Crea un volcado completo de la base de datos actual (estructura y registros). A diferencia de otras herramientas, no depende de utilidades externas como `mysqldump`, ya que utiliza el motor nativo de PDO.

**Lógica Interna:**
- Obtiene la estructura mediante `SHOW CREATE TABLE` (limpiando comillas invertidas).
- Escapa y formatea los datos en una sola sentencia `INSERT` masiva por tabla para optimizar la importación.
- Guarda el resultado en `db/database.mariadb.sql`.

**Ejemplos de Uso:**

```bash
# Generar respaldo manual
php comander/backup.php
```

---

## 4. Sincronizador de Permisos (sync_permissions.php)

Asegura que los permisos requeridos en el código existan en la base de datos. Escanea todos los archivos `router.php` buscando el patrón `->permission('clave')`.

**Lógica Interna:**
- Utiliza expresiones regulares para extraer claves de permiso.
- Compara los resultados con la tabla `permissions`.
- Elimina permisos huérfanos y registra los nuevos automáticamente.

**Ejemplos de Uso:**

```bash
# Sincronizar permisos tras modificar rutas
php comander/sync_permissions.php
```

---

## 5. Herramienta de Reseteo (reset.php)

Centraliza la limpieza de archivos temporales y optimizaciones del motor de PHP. Reemplaza a las antiguas utilidades `reset-cache.php` y `full-reset.php`.

**Lógica Interna:**
- Localiza y elimina archivos `.php` dentro de `storage/cache/`.
- Si se utiliza el flag `--full`, intenta resetear el **OPcache** de PHP para asegurar que los cambios en el código se reflejen inmediatamente.

**Ejemplos de Uso:**

```bash
# Limpiar caché de archivos (rutas, configuración, etc.)
php comander/reset.php

# Limpieza total (Caché + OPcache)
php comander/reset.php --full
```

---

## 6. Reseteo de Logos e Identidad (reset-logos.php)

Restaura la apariencia visual predeterminada de la aplicación (favicon, logos y metadata OpenGraph) tanto en archivos como en base de datos.

**Lógica Interna:**
- Limpia la carpeta de subidas de identidad (`storage/uploads/site`).
- Copia las imágenes originales desde `comander/images/site`.
- Actualiza las entradas correspondientes en la tabla `options`.

**Ejemplos de Uso:**

```bash
# Restaurar imagen corporativa original
php comander/reset-logos.php
```

---

## Resumen de Comandos Rápidos

| Herramienta | Comando | Acción Principal |
| :--- | :--- | :--- |
| **modules.php** | `create` | Scaffolding de módulos CRUD |
| **migrate.php** | `[fresh]` | Ejecución de scripts SQL estructurados |
| **backup.php** | `--help` | Volcado optimizado de base de datos (Bulk Insert) |
| **sync_permissions.php** | `--group` | Alineación de permisos Código vs DB |
| **reset.php** | `[--full]` | Purga de caché y opcionalmente OPcache |
| **reset-logos.php** | `--help` | Restauración de logos corporativos |

---

## Estándares de Salida (CLI)

Todas las herramientas han sido refactorizadas para proporcionar una salida limpia, profesional y libre de emojis, utilizando prefijos técnicos que facilitan la lectura en diversos entornos de terminal:

- `[SOLICITADO]`: Inicio de una acción o proceso.
- `[OK]`: Operación completada con éxito.
- `[INFO]`: Información relevante o estado del proceso.
- `[AVISO]`: Notificación importante que no detiene el proceso.
- `[ERROR]`: Fallo crítico que detiene la ejecución.