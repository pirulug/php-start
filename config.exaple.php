<?php

/*------------------------------------------------------------------------------
|
|  _____ _            _             
| |  __ (_)          | |            
| | |__) | _ __ _   _| |_   _  __ _ 
| |  ___/ | '__| | | | | | | |/ _` |
| | |   | | |  | |_| | | |_| | (_| |
| |_|   |_|_|   \__,_|_|\__,_|\__, |
|                              __/ |
|                             |___/ 
|                                     
| Author        : Pirulug
| Author URI    : https://github.com/pirulug
| Project       : PhpSTART
| Version       : 0.0.1
| License       : MIT
------------------------------------------------------------------------------*/

/*------------------------------------------------------------------------------
| DATABASE CONFIGURATION
|-------------------------------------------------------------------------------
| These constants are used to establish a connection with the database.
| Make sure to set proper values in production environments.
------------------------------------------------------------------------------*/

const DB_HOST = "localhost"; // Host de la base de datos
const DB_NAME = "php-start"; // Nombre de la BD
const DB_USER = "root"; // Usuario de la BD
const DB_PASS = ""; // Contraseña de la BD

/*------------------------------------------------------------------------------
| APPLICATION CONFIGURATION
|-------------------------------------------------------------------------------
| General application settings such as site name and URL.
------------------------------------------------------------------------------*/

const SITE_NAME = "Start PHP"; // Display name of the application
const SITE_URL = "http://php-start.test"; // Base URL (without trailing slash)

/*------------------------------------------------------------------------------
| ADMIN PANEL
|-------------------------------------------------------------------------------
| Defines the URL segment for accessing the admin panel.
| Example: http://php-start.test/panel
------------------------------------------------------------------------------*/

const ADMIN_NAME = "panel";

/*------------------------------------------------------------------------------
| PROJECT ROOT DIRECTORY
|-------------------------------------------------------------------------------
| Absolute path to the root of the project. 
| Useful for including files with absolute paths.
------------------------------------------------------------------------------*/

const BASE_DIR = __DIR__;

/*------------------------------------------------------------------------------
| SECURITY
|-------------------------------------------------------------------------------
| Encryption method and keys for secure data handling.
| ENCRYPT_METHOD: OpenSSL encryption algorithm (AES-256-CBC recommended)
| ENCRYPT_KEY   : Secret key for encryption (must be kept private)
| ENCRYPT_IV    : Initialization Vector (must be exactly 16 bytes for AES)
------------------------------------------------------------------------------*/

const ENCRYPT_METHOD = "AES-256-CBC";
const ENCRYPT_KEY = 'SGX|Y1BE3X;Y&T{Y[$OZ=Q98L|R5[YR@'; // Change this in production (keep it secret)
const ENCRYPT_IV = '0v05rgoamkhxuer1'; // Must be 16 bytes exactly for AES

/*------------------------------------------------------------------------------
| SUPER ADMIN
|-------------------------------------------------------------------------------
| Define los nombres de usuario que tendrán acceso total al sistema.
| Puedes agregar varios separados por comas.
------------------------------------------------------------------------------*/

const SUPERADMIN_USERNAMES = ['admin'];

const AUTO_SYNC_ROLE = true;

/*------------------------------------------------------------------------------
| DEFAULT TIMEZONE
|-------------------------------------------------------------------------------
| Defines the default timezone for the application.
| Make sure to set this according to your server or target audience.
------------------------------------------------------------------------------*/

date_default_timezone_set("America/Lima");