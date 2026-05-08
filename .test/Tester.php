<?php
/**
 * Tester
 * Clase minimalista para aserciones en pruebas unitarias.
 */
class Tester {
  protected static int $passed = 0;
  protected static int $failed = 0;
  protected static array $errors = [];

  /**
   * Verifica que dos valores sean iguales.
   *
   * @param mixed $expected Valor esperado.
   * @param mixed $actual Valor real.
   * @param string $message Mensaje de la prueba.
   */
  public static function assertEquals($expected, $actual, string $message): void {
    if ($expected === $actual) {
      self::$passed++;
      echo "  [OK] $message\n";
    } else {
      self::$failed++;
      $error = "  [FAIL] $message\n";
      $error .= "         Esperado: " . var_export($expected, true) . "\n";
      $error .= "         Recibido: " . var_export($actual, true) . "\n";
      self::$errors[] = $error;
      echo $error;
    }
  }

  /**
   * Verifica que un valor sea verdadero.
   *
   * @param mixed $value Valor a verificar.
   * @param string $message Mensaje de la prueba.
   */
  public static function assertTrue($value, string $message): void {
    self::assertEquals(true, $value, $message);
  }

  /**
   * Imprime el resumen de las pruebas.
   */
  public static function report(): void {
    echo "\n-----------------------------------------------------------------------------\n";
    echo "RESUMEN DE PRUEBAS\n";
    echo "-----------------------------------------------------------------------------\n";
    echo "Pasadas: " . self::$passed . "\n";
    echo "Fallidas: " . self::$failed . "\n";
    
    if (self::$failed > 0) {
      echo "\nDETALLE DE ERRORES:\n";
      foreach (self::$errors as $error) {
        echo $error . "\n";
      }
      exit(1);
    } else {
      echo "\n¡TODAS LAS PRUEBAS PASARON EXITOSAMENTE!\n";
      exit(0);
    }
  }
}
