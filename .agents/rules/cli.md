# Estándares de Comandos CLI (PS Console)

Este documento define las reglas inquebrantables para la creación de nuevos comandos en la consola `ps`. Todos los scripts dentro de `core/comander/` deben seguir esta estructura para garantizar consistencia y profesionalismo.

## 1. Estructura del Script

Todo comando debe dividirse en secciones semánticas claras utilizando comentarios delimitadores:

```php
<?php

/**
 * Descripción breve del propósito del comando.
 */

// =============================================================================
// SECCIÓN: CONFIGURACIÓN Y AYUDA
// =============================================================================

if (in_array('--help', $argv) || in_array('-h', $argv)) {
  echo "\nNombre del Comando\n";
  echo "--------------------------------------------------------\n";
  echo "Uso: php ps {comando} [argumentos] [opciones]\n\n";
  echo "Descripción:\n";
  echo "  Explicación concisa de qué hace el comando.\n";
  echo "--------------------------------------------------------\n\n";
  exit();
}

// =============================================================================
// SECCIÓN: LÓGICA DE EJECUCIÓN
// =============================================================================
```

## 2. Bloque de Ayuda (Obligatorio)

Cada comando **debe** implementar una sección de ayuda que se active con `--help` o `-h`. El diseño debe ser estrictamente en texto plano y seguir este formato:

- **Título**: Nombre del comando en la primera línea.
- **Separador**: Línea de guiones de 56 caracteres (`--------------------------------------------------------`).
- **Uso**: Ejemplo claro de ejecución.
- **Descripción/Detalles**: Lista de argumentos u opciones si aplica.

## 3. Estética y Salida por Terminal

Para mantener una interfaz limpia y profesional:

- **Sin Emojis**: Está estrictamente prohibido el uso de emojis en cualquier salida de la consola.
- **Separadores**: Usar la línea de guiones para separar el inicio, los bloques lógicos y el final de la ejecución.
- **Indicadores de Estado**:
  - `[SOLICITADO]`: Para indicar el inicio de una acción.
  - `[PROCESANDO]`: Para tareas iterativas.
  - `[OK]`: Para éxitos.
  - `[ERROR]`: Para fallos críticos.
- **Mayúsculas**: Los indicadores de estado y etiquetas de resumen (ej. ARCHIVO:) deben ir en MAYÚSCULAS.

## 4. Manejo de Errores

Toda lógica propensa a fallos (I/O, Base de Datos, Red) debe estar envuelta en bloques `try-catch`:

```php
try {
  // Lógica...
  echo "[OK] Operación completada.\n";
} catch (Exception $e) {
  echo "[ERROR] No se pudo completar la acción.\n";
  echo "DETALLE: " . $e->getMessage() . "\n";
  exit();
}
```

## 5. Convenciones de Nomenclatura

- **Archivos**: Deben guardarse en `core/comander/{nombre}.php`.
- **Variables**: Usar `snake_case` para variables locales del script.
- **Rutas**: Utilizar siempre la constante `BASE_DIR` para rutas absolutas.
