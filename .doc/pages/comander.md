# Comander

El directorio `comander/` concentra las utilidades de línea de comandos (CLI) del framework. Estas herramientas permiten automatizar desde la generación de código hasta el mantenimiento de la integridad de los datos, asegurando un flujo de trabajo ágil y estandarizado.

A continuación se detallan los **6 comandos** disponibles en el sistema:

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
- Obtiene la estructura mediante `SHOW CREATE TABLE`.
- Escapa y formatea los datos para asegurar la portabilidad del SQL generado.
- Guarda el resultado en `db/backup_YYYY-MM-DD_HHMMSS.sql`.

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

## 5. Limpiador de Caché (reset-cache.php)

Elimina archivos de optimización temporales generados por el sistema, fundamental al realizar cambios estructurales en entornos con caché activada.

**Lógica Interna:**
- Localiza y elimina archivos `.php` dentro de `storage/cache/`.
- Limpia específicamente el mapa de rutas (`router.cache.php`).

**Ejemplos de Uso:**

```bash
# Forzar recarga de rutas y configuración
php comander/reset-cache.php
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
| **backup.php** | *(S/A)* | Volcado de base de datos mediante PDO |
| **sync_permissions.php** | *(S/A)* | Alineación de permisos Código vs DB |
| **reset-cache.php** | *(S/A)* | Purga de archivos de caché de rutas |
| **reset-logos.php** | *(S/A)* | Restauración de logos corporativos |