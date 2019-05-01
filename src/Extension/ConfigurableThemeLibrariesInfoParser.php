<?php

namespace Drupal\configurable_theme_libraries\Extension;

use function array_diff;
use function array_intersect;
use function array_keys;
use function count;
use function implode;
use function sprintf;
use Drupal\Core\Extension\InfoParser;
use Drupal\Core\Extension\InfoParserException;

/**
 * Processes parsed theme info for configurable libraries.
 *
 * @see \Drupal\Core\Extension\InfoParser
 */
class ConfigurableThemeLibrariesInfoParser extends InfoParser {

  /**
   * {@inheritdoc}
   */
  public function parse($filename): array {
    $info = parent::parse($filename);
    if (empty($info['configurable-libraries'])) {
      return $info;
    }

    foreach ($info['configurable-libraries'] as $configurable_library) {
      $missing_keys = array_diff(['name'], array_keys($configurable_library));
      if (!empty($missing_keys)) {
        throw new InfoParserException(sprintf('The following required keys for a configurable library are missing: [%s] in %s', implode(', ', $missing_keys), $filename));
      }

      $library_keys = ['libraries', 'libraries-extend', 'libraries-override'];
      if (count(array_intersect($library_keys, array_keys($configurable_library))) === 0) {
        throw new InfoParserException(sprintf('The configurable library "%s" must at least define one of the following keys: [%s] in %s', $configurable_library['name'], implode(', ', $library_keys), $filename));
      }

    }

    return $info;
  }

}
