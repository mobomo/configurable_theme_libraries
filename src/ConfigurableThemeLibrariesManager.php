<?php

namespace Drupal\configurable_theme_libraries;

use Drupal\Core\Extension\ThemeHandlerInterface;

/**
 * Manager for configurable theme libraries.
 *
 * @package Drupal\configurable_theme_libraries
 */
class ConfigurableThemeLibrariesManager implements ConfigurableThemeLibrariesManagerInterface {

  /**
   * Theme extension info.
   *
   * @var \Drupal\Core\Extension\Extension[]
   */
  protected static $themeInfo;

  /**
   * Constructs a Configurable Theme Libraries Manager.
   *
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler.
   */
  public function __construct(ThemeHandlerInterface $theme_handler) {
    self::$themeInfo = $theme_handler->listInfo();
  }

  /**
   * Check whether a configurable library is enabled for a given theme.
   *
   * @param string $theme
   *   The theme name.
   * @param string $library
   *   The library.
   *
   * @return bool
   *   Whether a configurable library is enabled for a given theme.
   */
  public static function isEnabled(string $theme, string $library): bool {
    $settings = \theme_get_setting('configurable_libraries.libraries', $theme);
    return !empty($settings[$library]);
  }

  /**
   * Get configurable libraries for a theme.
   *
   * @param string $theme
   *   The theme name.
   *
   * @return array
   *   The configurable libraries for the theme.
   */
  protected static function getConfigurableLibraries(string $theme): array {
    return self::$themeInfo[$theme]->info['configurable-libraries'] ?? [];
  }

  /**
   * Check whether a theme has any configurable libraries.
   *
   * @param string $theme
   *   The theme name.
   *
   * @return bool
   *   Whether the theme has any configurable libraries.
   */
  protected static function hasConfigurableLibraries(string $theme): bool {
    return !empty(self::getConfigurableLibraries($theme));
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigurableLibrariesForm(string $theme): array {
    if (!self::hasConfigurableLibraries($theme)) {
      return [];
    }

    $libraries = self::getConfigurableLibraries($theme);
    $form['configurable_libraries'] = [
      '#type' => 'details',
      '#tree' => TRUE,
      '#title' => t('Additional libraries'),
      '#description' => t('Additional libraries can be enabled for this theme. Make sure to be cautious which libraries you select, as there is a possibility that they rule each other out.'),
      '#open' => TRUE,
      'libraries' => [
        '#type' => 'checkboxes',
        '#title' => t('Libraries'),
        '#default_value' => \theme_get_setting('configurable_libraries.libraries', $theme) ?? [],
        '#options' => \array_combine(\array_keys($libraries), \array_column($libraries, 'name')),
      ],
    ];

    foreach ($libraries as $i => $library) {
      $form['configurable_libraries']['libraries'][$i] = ['#description' => $library['description'] ?? ''];
    }

    return $form;
  }

}
