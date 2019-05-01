<?php

namespace Drupal\configurable_theme_libraries;

/**
 * Interface definition for a configurable theme libraries manager.
 *
 * @package Drupal\configurable_theme_libraries
 */
interface ConfigurableThemeLibrariesManagerInterface {

  /**
   * Get the (sub) form to configure which libraries the theme will use.
   *
   * @param string $theme
   *   The theme name.
   *
   * @return array
   *   The configurable libraries form.
   */
  public function getConfigurableLibrariesForm(string $theme): array;

}
