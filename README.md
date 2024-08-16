# PHP Start

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

PHP Start es un miniframework ligero diseñado para facilitar el desarrollo rápido de aplicaciones web en PHP. Está construido con un enfoque en la simplicidad, el rendimiento y la flexibilidad.

## Características

- **Ligero y rápido**: Sin dependencias innecesarias, se centra en lo esencial para maximizar el rendimiento.
- **Control de acceso basado en roles**: Implementación flexible de roles (superadmin, admin, usuario) para gestionar permisos.
- **Manejo de sesiones**: Gestión de sesiones sencilla y efectiva con redirección automática en caso de errores.
- **Configurable**: Fácil de personalizar y extender según las necesidades de tu proyecto.
- **Estructura de directorios intuitiva**: Facilita la organización del código y recursos.

## Instalación

1. Clona el repositorio:

    ```bash
    git clone https://github.com/pirulug/php-start.git
    ```

2. Navega al directorio del proyecto:

    ```bash
    cd php-start
    ```

3. Configura tu servidor local (Apache/Nginx) para apuntar al directorio `public/`.

4. Asegúrate de que tu servidor tiene PHP 8.0 o superior.

## Estructura del Proyecto

```plaintext
php-start/
|── admin
|── assets
|── install
|── libs
|── uploads
|── views
|── index.md
└── README.md
```

## Uso
### Control de Acceso Basado en Roles
El miniframework incluye una clase AccessControl que permite gestionar permisos según roles. Los roles disponibles son:

- Superadmin (rol 0): Acceso total.
- Admin (rol 1): Acceso con ciertas restricciones.
- Usuario (rol 2): Acceso limitado.

Ejemplo de uso:

```php
$accessControl = new AccessControl($userRole);
if ($accessControl->hasAccess([1, 2])) {
    // El usuario tiene acceso
} else {
    // El usuario no tiene acceso
}
```

## Manejo de Sesiones

La clase SessionManager facilita la gestión de sesiones y redirecciones automáticas:

```php
$session = new SessionManager();
if (!$session->isLoggedIn()) {
    $session->redirect('login.php');
}
```

## Contribuir

¡Las contribuciones son bienvenidas! Siéntete libre de abrir un issue o enviar un pull request.

## Haz un fork del proyecto.
- Crea una nueva rama (git checkout -b feature/nueva-feature).
- Haz tus cambios y haz commit (git commit -am 'Agrega una nueva característica').
- Haz push a la rama (git push origin feature/nueva-feature).
- Abre un Pull Request.

## Licencia
Este proyecto está licenciado bajo la licencia MIT. Consulta el archivo LICENSE para más detalles.

## Contacto
Si tienes alguna pregunta o sugerencia, no dudes en abrir un issue o contactar a través de GitHub.