<?php

/*--------------------------------------------------
| Author       : Pirulug
| Author URI   : https://pirulug.github.io
----------------------------------------------------*/

// Configuración de la base de datos
const DB_HOST = "localhost";
const DB_NAME = "php-start";
const DB_USER = "root";
const DB_PASS = "";

// Configuración de la aplicación
const APP_NAME = "Start PHP";
const APP_URL = "http://php-start.test"; // Sin "/" al final de la url

// Directorio Base
const BASE_DIR = __DIR__;
const BASE_DIR_ADMIN = __DIR__ . "/admin";
const BASE_DIR_ADMIN_VIEW = __DIR__ . "/admin/views";

// Claves
const METHOD = "AES-256-CBC";
const SECRET_KEY = '$STARTPHP@2024PIRU';
const SECRET_IV = '456232132132432234132';

// Configuración de la zona horaria
date_default_timezone_set("America/Lima");