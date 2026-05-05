<?php

/**
 * SiteDate
 *
 * Clase encargada de la gestión centralizada de fechas y horas.
 * Soporta conversión de zonas horarias basadas en la configuración del sitio,
 * traducción automática de meses y días al español y formateo dinámico.
 *
 * @author Pirulug
 * @link   https://github.com/pirulug
 */
class SiteDate {
  // --------------------------------------------------------------------------
  // PROPIEDADES DE ESTADO
  // --------------------------------------------------------------------------

  private SiteConfig $config;
  private DateTimeZone $timezone;

  /** @var array Mapa de traducciones para fechas en español */
  private array $translations = [
    'January'   => 'Enero',
    'February'  => 'Febrero',
    'March'     => 'Marzo',
    'April'     => 'Abril',
    'May'       => 'Mayo',
    'June'      => 'Junio',
    'July'      => 'Julio',
    'August'    => 'Agosto',
    'September' => 'Septiembre',
    'October'   => 'Octubre',
    'November'  => 'Noviembre',
    'December'  => 'Diciembre',
    'Jan'       => 'Ene',
    'Feb'       => 'Feb',
    'Mar'       => 'Mar',
    'Apr'       => 'Abr',
    'Jun'       => 'Jun',
    'Jul'       => 'Jul',
    'Aug'       => 'Ago',
    'Sep'       => 'Sep',
    'Oct'       => 'Oct',
    'Nov'       => 'Nov',
    'Dec'       => 'Dic',
    'Monday'    => 'Lunes',
    'Tuesday'   => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday'  => 'Jueves',
    'Friday'    => 'Viernes',
    'Saturday'  => 'Sábado',
    'Sunday'    => 'Domingo',
    'Mon'       => 'Lun',
    'Tue'       => 'Mar',
    'Wed'       => 'Mié',
    'Thu'       => 'Jue',
    'Fri'       => 'Vie',
    'Sat'       => 'Sáb',
    'Sun'       => 'Dom',
    'am'        => 'am',
    'pm'        => 'pm',
    'AM'        => 'AM',
    'PM'        => 'PM',
  ];

  /**
   * Constructor: inicializa la zona horaria del sistema.
   *
   * @param SiteConfig|null $config Instancia opcional. Si es null, usa la global.
   */
  public function __construct(?SiteConfig $config = null) {
    global $config;
    $this->config = $config;

    $tzName = $this->config->site_timezone ?? 'America/Lima';
    try {
      $this->timezone = new DateTimeZone($tzName);
    } catch (Exception $e) {
      $this->timezone = new DateTimeZone('UTC');
    }
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: FORMATEO UNIVERSAL
  // --------------------------------------------------------------------------

  /**
   * Formateador universal de fechas con soporte de traducción.
   *
   * @param mixed $date String, timestamp o DateTime.
   * @param string|null $format Formato PHP o alias (date, time, datetime).
   * @return string Fecha formateada y traducida.
   */
  public function format(mixed $date = 'now', ?string $format = null): string {
    $dateTime = $this->createDateTimeObject($date);

    if (!$dateTime) {
      return 'Fecha Inválida';
    }

    $dateTime->setTimezone($this->timezone);

    if (!$format) {
      $format = $this->config->datetime_format ?? 'd/m/Y H:i a';
    } else {
      switch ($format) {
        case 'date':
          $format = $this->config->date_format ?? 'd/m/Y';
          break;
        case 'time':
          $format = $this->config->time_format ?? 'H:i a';
          break;
        case 'datetime':
          $format = $this->config->datetime_format ?? 'd/m/Y H:i a';
          break;
      }
    }

    $rawOutput = $dateTime->format($format);

    return str_replace(
      array_keys($this->translations),
      array_values($this->translations),
      $rawOutput
    );
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: ATAJOS DE FORMATO
  // --------------------------------------------------------------------------

  /**
   * Atajo para obtener solo la fecha.
   */
  public function date(mixed $date = 'now'): string {
    return $this->format($date, 'date');
  }

  /**
   * Atajo para obtener solo la hora.
   */
  public function time(mixed $date = 'now'): string {
    return $this->format($date, 'time');
  }

  /**
   * Atajo para obtener fecha y hora completas.
   */
  public function datetime(mixed $date = 'now'): string {
    return $this->format($date, 'datetime');
  }

  // --------------------------------------------------------------------------
  // SECCIÓN: LÓGICA INTERNA
  // --------------------------------------------------------------------------

  /**
   * Crea un objeto DateTime a partir de diversos tipos de entrada.
   *
   * @param mixed $input Entrada de fecha.
   * @return DateTime|false
   */
  private function createDateTimeObject(mixed $input): DateTime|false {
    if ($input instanceof DateTime) {
      return clone $input;
    }

    if (is_numeric($input)) {
      $dt = new DateTime();
      $dt->setTimestamp($input);
      return $dt;
    }

    if (empty($input) || strtolower($input) === 'now') {
      return new DateTime('now');
    }

    try {
      return new DateTime($input);
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Obtiene el objeto de zona horaria actual.
   *
   * @return DateTimeZone
   */
  public function getTimezone(): DateTimeZone {
    return $this->timezone;
  }
}