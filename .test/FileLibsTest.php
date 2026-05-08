<?php
/**
 * FileLibsTest
 * Pruebas para UploadFile, UploadImage y FaviconGenerator.
 */

echo "\nEJECUTANDO: FileLibsTest\n";

// 1. Prueba: UploadFile (Configuración)
$uploader = new UploadFile();
$uploader->dir(BASE_DIR . "/storage/tests")
         ->allowedTypes(["pdf", "zip"])
         ->maxSize(1024)
         ->prefix("test_")
         ->unique();

$reflection = new ReflectionClass($uploader);
$dir = $reflection->getProperty("uploadDir");
$dir->setAccessible(true);
Tester::assertTrue(strpos($dir->getValue($uploader), "storage/tests") !== false, "UploadFile debe guardar el directorio correctamente");

$types = $reflection->getProperty("allowedTypes");
$types->setAccessible(true);
Tester::assertTrue(in_array("pdf", $types->getValue($uploader)), "UploadFile debe guardar las extensiones permitidas");

// 2. Prueba: UploadImage (Configuración)
$imgUploader = new UploadImage();
$imgUploader->dir(BASE_DIR . "/storage/tests/images")
            ->convertTo("webp")
            ->optimize(8)
            ->resize("thumb", 100, 100);

$reflectionImg = new ReflectionClass($imgUploader);
$conv = $reflectionImg->getProperty("convertTo");
$conv->setAccessible(true);
Tester::assertEquals("webp", $conv->getValue($imgUploader), "UploadImage debe configurar el formato de conversión");

// 3. Prueba: FaviconGenerator
$favGen = new FaviconGenerator(BASE_DIR . "/storage/tests/favicon");
Tester::assertTrue(is_dir(BASE_DIR . "/storage/tests/favicon"), "FaviconGenerator debe crear el directorio de salida");
