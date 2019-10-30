<?php

namespace Drupal\configurable_theme_libraries\Theme;

use function array_merge;
use Drupal\configurable_theme_libraries\ConfigurableThemeLibrariesManager;
use Drupal\Core\Extension\Extension;
use Drupal\Core\Theme\ActiveTheme;
use Drupal\Core\Theme\ThemeInitialization as CoreThemeInitialization;

/**
 * Provides the theme initialization logic.
 */
class ThemeInitialization extends CoreThemeInitialization {

  /**
   * The active theme values.
   *
   * @var array
   */
  protected $activeThemeValues;

  /**
   * {@inheritdoc}
   */
  public function getActiveTheme(Extension $theme, array $base_themes = []): ActiveTheme {
    $active_theme = parent::getActiveTheme($theme, $base_themes);
    if (empty($theme->info['configurable-libraries'])) {
      return $active_theme;
    }

    $this->activeThemeValues = [
      'name' => $active_theme->getName(),
      'path' => $active_theme->getPath(),
      'engine' => $active_theme->getEngine(),
      'owner' => $active_theme->getOwner(),
      'logo' => $active_theme->getLogo(),
      'stylesheets_remove' => $this->prepareStylesheetsRemove($theme, $base_themes),
      'libraries' => $active_theme->getLibraries(),
      'extension' => $active_theme->getExtension(),
      'base_theme_extensions' => $active_theme->getBaseThemeExtensions(),
      'regions' => $theme->info['regions'] ?? [],
      'libraries_override' => $active_theme->getLibrariesOverride(),
      'libraries_extend' => $active_theme->getLibrariesExtend(),
    ];

    $overridden = FALSE;
    foreach ($theme->info['configurable-libraries'] as $id => $configurable_library) {
      if (!ConfigurableThemeLibrariesManager::isEnabled($theme->getName(), $id)) {
        continue;
      }

      $overridden = TRUE;
      $this->addLibrariesOverride($configurable_library, $theme);
      $this->addLibrariesExtend($configurable_library);
      $this->addLibraries($configurable_library);
    }

    return $overridden ? new ActiveTheme($this->activeThemeValues) : $active_theme;
  }

  /**
   * Add library overrides.
   *
   * @param array $configurable_library
   *   The configurable library definition.
   * @param \Drupal\Core\Extension\Extension $theme
   *   The theme extension.
   */
  protected function addLibrariesOverride(array $configurable_library, Extension $theme): void {
    if (empty($configurable_library['libraries-override'])) {
      return;
    }

    foreach ($configurable_library['libraries-override'] as $library => $override) {
      $this->activeThemeValues['libraries_override'][$theme->getPath()][$library] = $override;
    }
  }

  /**
   * Add library extensions.
   *
   * @param array $configurable_library
   *   The configurable library definition.
   */
  protected function addLibrariesExtend(array $configurable_library): void {
    if (empty($configurable_library['libraries-extend'])) {
      return;
    }

    foreach ($configurable_library['libraries-extend'] as $library => $extend) {
      $this->activeThemeValues['libraries_extend'][$library] = isset($this->activeThemeValues['libraries_extend'][$library]) ? array_merge($this->activeThemeValues['libraries_extend'][$library], $extend) : $extend;
    }
  }

  /**
   * Add libraries.
   *
   * @param array $configurable_library
   *   The configurable library definition.
   */
  protected function addLibraries(array $configurable_library): void {
    if (empty($configurable_library['libraries'])) {
      return;
    }

    foreach ($configurable_library['libraries'] as $library) {
      $this->activeThemeValues['libraries'][] = $library;
    }
  }

}
