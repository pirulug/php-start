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
| Project       : PhpInstaller
| Version       : 0.0.1
| License       : MIT
------------------------------------------------------------------------------*/

/**
 * =============================================================================
 * CONFIGURACIÓN DEL PROYECTO
 * =============================================================================
 * Este archivo centraliza todas las constantes globales.
 * No modificar directamente en producción.
 */

/*---------------------------------------------------------------
| BASE DE DATOS
----------------------------------------------------------------*/
const DB_HOST = "localhost"; // Host de la base de datos
const DB_NAME = "php-start"; // Nombre de la BD
const DB_USER = "root"; // Usuario de la BD
const DB_PASS = ""; // Contraseña de la BD

/*---------------------------------------------------------------
| APLICACIÓN
----------------------------------------------------------------*/
const SITE_NAME = "Start PHP";

const SITE_URL = "http://php-start.test"; // Sin "/" al final

// -----------------------------------------------------------------------------
// Nombre para acceder al panel administración
// -----------------------------------------------------------------------------

const ADMIN_NAME = "panel";

// -----------------------------------------------------------------------------
// Directorio raíz del proyecto
// -----------------------------------------------------------------------------

const BASE_DIR = __DIR__; 

/*---------------------------------------------------------------
| SEGURIDAD
----------------------------------------------------------------*/
const ENCRYPT_METHOD = "AES-256-CBC"; // Método de cifrado
const ENCRYPT_KEY = '$STARTPHP@2024PIRU'; // Llave de cifrado (cambiar en prod)
const ENCRYPT_IV = '456232132132432234132'; // IV (debe tener 16 bytes exactos en AES)

/*---------------------------------------------------------------
| SISTEMA
----------------------------------------------------------------*/
date_default_timezone_set("America/Lima"); // Zona horaria por defecto