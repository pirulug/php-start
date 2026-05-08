# Comander (CLI Tools)

El directorio `core/comander/` concentra las utilidades de línea de comandos (CLI) del framework. Sin embargo, para agilizar el flujo de trabajo, se utiliza el despachador **`ps`** ubicado en la raíz del proyecto.

Estas herramientas permiten automatizar desde la generación de código (scaffolding) hasta el mantenimiento de la integridad de los datos y el sistema de traducciones.

---

## Uso General

Todos los comandos se ejecutan a través del script `ps` seguido del nombre del comando:

```bash
php ps [comando] [opciones]
```

Para ver la ayuda de un comando específico, puedes usar:
```bash
php ps [comando] --help
```

---

## Comandos Disponibles

### 1. Generador de Módulos (`modules`)
Es la herramienta de andamiaje principal. Automatiza la creación de la estructura de carpetas, controladores, vistas, enrutadores y menús.

**Sintaxis:**
`php ps modules create [plural] [singular] --context=[contexto]`

**Parámetros:**
- `plural`: Nombre del módulo en plural (nombre de la carpeta).
- `singular`: Nombre del recurso en singular.
- `--context`: Contexto de la aplicación (`admin`, `front`, `api`).

**Ejemplo:**
```bash
# Crear un módulo administrativo para 'Productos'
php ps modules create products product --context=admin
```

---

### 2. Migrador de Base de Datos (`migrate`)
Gestiona la ejecución de scripts SQL de forma ordenada. Escanea la carpeta `db/` buscando archivos numerados (ej. `01_base.sql`).

**Opciones:**
- `fresh`: Elimina todas las tablas y vuelve a ejecutar todas las migraciones desde cero.

**Ejemplos:**
```bash
# Ejecutar migraciones pendientes
php ps migrate

# Reiniciar base de datos completa
php ps migrate fresh
```

---

### 3. Generador de Idiomas (`lang`)
Escanea el código fuente de un módulo en busca de funciones de traducción (`__()`, `_e()`) y genera/actualiza los archivos de idioma.

**Opciones:**
- `-m, --module`: Nombre del módulo.
- `-c, --context`: Contexto (`admin`, `front`).
- `-cp, --copy`: Copia el archivo `es.php` generado a otro idioma (ej: `en`).

**Ejemplo:**
```bash
# Generar traducciones para el módulo 'users' y copiar a inglés
php ps lang -m users -c admin --copy=en
```

---

### 4. Sincronizador de Permisos (`sync-permissions`)
Asegura que los permisos definidos en los archivos `router.php` (mediante `->permission()`) existan en la base de datos y elimina los que ya no se usan.

**Uso:**
```bash
php ps sync-permissions
```

---

### 5. Respaldo de Base de Datos (`backup`)
Crea un volcado (dump) completo de la base de datos en `db/database.mariadb.sql` utilizando sentencias `INSERT` masivas para optimizar la importación.

**Uso:**
```bash
php ps backup
```

---

### 6. Herramienta de Reseteo (`reset`)
Limpia los archivos temporales y optimiza el motor de PHP.

**Opciones:**
- `--full`: Además de la caché de archivos, intenta resetear el **OPcache** de PHP.

**Ejemplo:**
```bash
# Limpiar caché de rutas y configuración
php ps reset
```

---

### 7. Reseteo de Identidad (`reset-logos`)
Restaura la apariencia visual predeterminada (logos, favicon y metadata) tanto en archivos como en base de datos.

**Uso:**
```bash
php ps reset-logos
```

---

## Tabla de Referencia Rápida

| Comando | Acción Principal |
| :--- | :--- |
| `modules` | Scaffolding de módulos (Admin/Front/API) |
| `migrate` | Ejecución y gestión de scripts SQL |
| `lang` | Extracción y generación de archivos I18N |
| `sync-permissions` | Alineación de permisos Código vs Base de Datos |
| `backup` | Respaldo optimizado de la base de datos |
| `reset` | Limpieza de caché del sistema |
| `reset-logos` | Restauración de identidad corporativa |

---

## Estándares de Salida (CLI)

Todas las herramientas utilizan prefijos técnicos para facilitar la lectura:

- `[SOLICITADO]`: Inicio de una acción.
- `[OK]`: Operación completada con éxito.
- `[INFO]`: Información de estado o depuración.
- `[AVISO]`: Notificación importante (no crítica).
- `[ERROR]`: Fallo crítico que detiene la ejecución.