# Documentación: Uso de la clase `MessageHandler` en PHP

La clase `MessageHandler` permite gestionar diferentes tipos de mensajes y notificaciones, incluyendo mensajes de Bootstrap, notificaciones Toastify y SweetAlert2. Esta guía explica cómo inicializar la clase y usar sus métodos.

---

## Inicialización de la clase

Antes de usar cualquier funcionalidad de `MessageHandler`, es necesario inicializarla:

```php
$messageHandler = new MessageHandler();
```

---

## Métodos principales

### 1. **Agregar mensajes**

La clase permite agregar mensajes de diferentes tipos. Cada mensaje requiere tres parámetros:

- **Texto del mensaje**: El contenido del mensaje.
    
- **Tipo de alerta**: Puede ser `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, o `dark`.
    
- **Estilo**: Define cómo se mostrará el mensaje: `bootstrap`, `toast`, o `sweetalert`.
    

#### Ejemplo:

```php
// Mensajes de Bootstrap
$messageHandler->addMessage("Este es un mensaje de alerta primaria", "primary", "bs");

// Notificaciones Toastify
$messageHandler->addMessage("Este es un Toastify de información", "info", "tf");

// Mensajes SweetAlert2
$messageHandler->addMessage("Este es un mensaje SweetAlert2 de éxito", "success", "sa");
```

---

### 2. **Verificar la existencia de mensajes por tipo**

El método `hasMessagesOfType` permite comprobar si existen mensajes de un tipo específico en la sesión.

#### Ejemplo:

```php
if ($messageHandler->hasMessagesOfType('danger')) {
  echo "<p>Hay mensajes de tipo 'danger' en la sesión.</p>";
}
```

---

### 3. **Mostrar mensajes**

La clase tiene métodos para mostrar los mensajes almacenados, dependiendo del estilo seleccionado:

- **Mensajes de Bootstrap**: `displayMessages()`
    
- **Notificaciones Toastify**: `displayToasts()`
    
- **Notificaciones SweetAlert2**: `displaySweetAlerts()`
    

#### Ejemplo:

```php
// Mostrar mensajes de Bootstrap
$messageHandler->displayMessages();

// Mostrar notificaciones Toastify
$messageHandler->displayToasts();

// Mostrar notificaciones SweetAlert2
$messageHandler->displaySweetAlerts();
```

---

## Ejemplo completo de uso

```php
// Inicializar la clase
$messageHandler = new MessageHandler();

// Agregar mensajes de diferentes estilos
$messageHandler->addMessage("Mensaje de alerta primaria", "primary", "bootstrap");
$messageHandler->addMessage("Toastify de información", "info", "toast");
$messageHandler->addMessage("SweetAlert2 de éxito", "success", "sweetalert");

// Verificar si hay mensajes de tipo específico
if ($messageHandler->hasMessagesOfType('danger')) {
  echo "<p>Hay mensajes de tipo 'danger'.</p>";
}

// Mostrar los mensajes
$messageHandler->displayMessages();
$messageHandler->displayToasts();
$messageHandler->displaySweetAlerts();
```

---

## Tipos de mensajes soportados

### 1. **Bootstrap**

Mensajes de alerta con los estilos de Bootstrap: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`.

### 2. **Toastify**

Notificaciones ligeras con soporte para `info`, `success`, `danger`, `warning`, `primary`, `secondary`, `light`, `dark`.

### 3. **SweetAlert2**

Mensajes emergentes con soporte para los tipos `success`, `danger`, `warning`, `info`.

---

## Notas adicionales

- Asegúrate de incluir los recursos necesarios para Bootstrap, Toastify y SweetAlert2 en tu proyecto.
    
- Configura la sesión de PHP si los mensajes se almacenan en ella.
    

---

¡Con esta documentación podrás aprovechar al máximo las funcionalidades de la clase `MessageHandler`!