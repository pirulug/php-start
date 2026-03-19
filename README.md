# PHP Start

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

**PHP Start** is a lightweight, scalable, and modular mini-framework for rapid web application development in PHP. It is built strictly with raw PHP without huge external dependencies, offering a powerful custom foundation featuring dynamic Module generation, robust Security routing, and comprehensive Asset caching out of the box.

## Main Features

- **Lightweight & Fast:** Built for speed with file-based routing cache (`CACHE_ROTE`).
- **Comander CLI Tools:** Built-in console utility to rapidly scaffold CRUD modules and maintain the system.
- **Context-driven Routing:** Clean segregation between UI Panels (`admin`), Landing (`home`), and endpoints (`api`/`ajax`).
- **Advanced Security:** Native `AntiXSS` sanitization, PDO wrappers against SQLi, and IP-Spoofing resistant Analytics & Rate Limiters.
- **Dynamic Asset Uploads:** Intelligent Image and File processors with webp conversion and WebShell prevention.
- **Role-based Access Control:** Fully integrated Middleware & Permissions (`can()`) system.

## Project Structure

```plaintext
php-start/
├── app/                # Business logic (Contexts: admin, api, ajax, home)
├── comander/           # Development CLI Tools (Code Generator, cache resets, etc)
├── core/               # Framework heart (Bootstrap, middlewares, libs, helpers)
├── db/                 # Database migrations and seeders
├── install/            # Installation wizards and default assets
├── static/             # Public static web assets (CSS, JS, Fonts)
├── storage/            # Ignored folder for Logs, Route Caching, and temporary Uploads
├── config.php          # Main credentials and configuration
└── index.php           # Front Controller and Main Entry Point
```

## Developing with PHP-Start (Code Examples)

### 1. Database Connections and Queries
The framework abstracts PDO connections via the `DataBase` class. All variables passed to the DB should be bound explicitly via PDO to prevent SQL Injection.

```php
// Selecting data
$query = "SELECT * FROM users WHERE user_status = :status AND role_id = :role";
$stmt = $connect->prepare($query);
$stmt->execute([':status' => 1, ':role' => 2]);
$users = $stmt->fetchAll(PDO::FETCH_OBJ);

// Inserting data
$query = "INSERT INTO categories (name, created_at) VALUES (:name, NOW())";
$stmt = $connect->prepare($query);
$stmt->execute([':name' => $category_name]);
```

### 2. File and Image Uploads
PHP-Start provides a powerful `UploadImage` class that regenerates thumbnails to prevent WebShell embeds and normalizes image forms (e.g. converting to `.webp`).

```php
$uploader = (new UploadImage())
  ->dir(BASE_DIR . "/storage/uploads/avatars")
  ->file($_FILES['avatar'])
  ->supported(['jpg', 'jpeg', 'png', 'webp'])
  ->convertTo('webp')   // Enforces WebP modern format
  ->optimize(8)         // Image quality 0 to 10
  ->resize('thumb', 300, 300); // Automatically crops a 300x300 variation

$result = $uploader->upload();

if ($result['success']) {
    echo "Image path: " . $result['file_path'];
    echo "Thumb path: " . $result['resized']['thumb']['path'];
} else {
    echo "Error: " . $result['message'];
}
```

### 3. Middleware and Routing
You can bind Middlewares and Permissions directly to the router chain to secure specific admin endpoints.

```php
// app/admin/modules/reports/router.php
Router::route('report/financial')
  ->action(admin_action("reports.financial"))
  ->view(admin_view("reports.financial"))
  ->layout(admin_layout())
  ->middleware('auth_admin')
  ->permission("reports.view_financials") // Requires exact DB permission node
  ->register();
```

## Comander (CLI Developer Tools)

PHP-Start includes a powerful CLI utility inside the `comander/` directory that speeds up your development and simplifies maintenance tasks.

### Rapid Module Generator (CRUD)
Instead of wiring controllers and views manually, you can scaffold an entire module in seconds.

```bash
php comander/modules.php create products product --context=admin
```

This command will automatically:
- Generate 4 independent action controllers (`list.action.php`, `new.action.php`, `edit.action.php`, `delete.action.php`).
- Scaffold base HTML Views (`list.view.php`, `new.view.php`, `edit.view.php`).
- Interconnect those actions by rewriting a local `router.php`.
- Wire a dynamic grouped Sidebar in `menu.php` (e.g. "Products -> New Product | Product List").
- Auto-register the new module securely in `app/admin/modules.php`.

### System Actions
- **Reset Cache (`php comander/reset-cache.php`)**: Purges the route cache inside `storage/cache/` so new files or structural changes take effect immediately.
- **Reset Logos (`php comander/reset-logos.php`)**: Quickly restores the default favicons, site icons, and DB settings to the default "fresh install" format.

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/pirulug/php-start.git
    ```
2. **Access the directory:**
    ```bash
    cd php-start
    ```
3. **Database Setup:**
   Import your database scheme and update `config.php` with your local DB credentials.
4. **Local Server:**
   Point your Apache/Nginx (e.g., Laragon, XAMPP) root directory to the project's folder.
5. Make sure your server is running PHP 8.0 or higher.

## License
This project is licensed under the MIT License. See the LICENSE file for details.