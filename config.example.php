<?php

/*--------------------------------------------------
| Author       : Pirulug
| Author URI   : https://github.com/pirulug
----------------------------------------------------*/

// Configuración de la base de datos
const DB_HOST = "HOST_DB";
const DB_NAME = "NAME_DB";
const DB_USER = "USER_DB";
const DB_PASS = "PASS_DB";

// Configuración de la aplicación
const APP_NAME = "Start PHP";
const APP_URL = "URL_WEB"; // Sin "/" al final de la url

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