
# PHP Start

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

**PHP Start** is a lightweight and modular mini-framework for rapid web application development in PHP. It is designed for simplicity, performance, and flexibility, allowing you to build scalable and well-organized projects.

## Main Features

- **Lightweight & Fast:** No unnecessary external dependencies.
- **Role-based Access Control:** Superadmin, admin, and user roles.
- **Advanced Session Management:** Automatic redirection and protection.
- **Custom Template Engine:** Clear separation between logic and views.
- **Notification System:** Supports Bootstrap, SweetAlert, and Toast.
- **Modular Structure:** Organized folders for admin, API, pages, static resources, and libraries.

## Project Structure

```plaintext
php-start/
├── app/                # Business logic (admin, api, pages)
├── core/               # Initialization, configuration, helpers
├── libs/               # Reusable libraries (AccessControl, Encryption, etc.)
├── routers/            # Routers for admin, API, and frontend
├── static/             # Static resources (CSS, JS, images, plugins)
├── uploads/            # User and site uploaded files
├── config.php          # Main configuration
├── index.php           # Main entry point
├── README.md           # Documentation
```

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/pirulug/php-start.git
    ```
2. Enter the project directory:
    ```bash
    cd php-start
    ```
3. Configure your local server (Apache/Nginx) to point to the root directory.
4. Make sure your server is running PHP 8.0 or higher.

## Usage Example

### Role-based Access Control

```php
$accessControl = new AccessControl($userRole);
if ($accessControl->hasAccess([1, 2])) {
    // User has access
} else {
    // User does not have access
}
```

### Session Management

```php
$session = new SessionManager();
if (!$session->isLoggedIn()) {
    $session->redirect('login.php');
}
```

### Notifications

```php
$notifier = new Notifier();
$notifier->notify('Welcome!', 'success', 'sweetalert');
```

## Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request.

## Fork the Project
- Create a new branch (`git checkout -b feature/new-feature`).
- Make your changes and commit (`git commit -am 'Add a new feature'`).
- Push to the branch (`git push origin feature/new-feature`).
- Open a Pull Request.

## License
This project is licensed under the MIT License. See the LICENSE file for details.

## Contact
If you have any questions or suggestions, feel free to open an issue or contact via GitHub.