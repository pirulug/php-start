<?php

/*-------------------------------------------------------------------
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
| Project       : PhpInstaller
| Version       : 0.0.0
| License       : MIT
---------------------------------------------------------------------*/

// Configuración de la base de datos
const DB_HOST = "<DB_HOST>";
const DB_NAME = "<DB_NAME>";
const DB_USER = "<DB_USER>";
const DB_PASS = "<DB_PASSWORD>";

// Configuración de la aplicación
const SITE_NAME = "<SITE_NAME>";
const SITE_URL = "<SITE_URL>";
const SITE_URL_ADMIN = SITE_URL . "/admin";

// Directorio Base
const BASE_DIR = __DIR__;
const BASE_DIR_ADMIN = __DIR__ . "/admin";

// Claves
const ENCRYPT_METHOD = "AES-256-CBC";
const SECRET_KEY = '<SECRET_KEY>';
const SECRET_IV = '<SECRET_IV>';

// Configuración de la zona horaria
date_default_timezone_set("America/Lima");