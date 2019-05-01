# CONFIGURABLE THEME LIBRARIES

_Thanks for taking the time to check this readme!_

## INTRODUCTION

This module provides a neat solution to define configurable theme libraries in 
your _THEME_.info.yml file. These libraries can be enabled or disabled for a
given theme.

## REQUIREMENTS
This module has no hard dependencies.

## CONFIGURATION
But, to make it work for your theme there are a few steps you should take:

* Define at least one configurable library in your _THEME_.info.yml. Defining 
them is done by using the `configurable-libraries` key. This module scans 
the active theme for these to register them.

Each configurable library can contain their own library definitions as normally
used in the _THEME_.info.yml. This consists of:
- libraries
- libraries-extend
- libraries-override

An example definition looks like:
```
configurable-libraries:
  global-styling-green:
    name: Global Styling (Green)
    libraries:
      - my_theme/global-styling-green
    libraries-override:
      - my_theme/global-styling: false
  global-styling-blue:
    name: Global Styling (Blue)
    description: This makes the site blue.
    libraries:
      - my_theme/global-styling-blue
    libraries-override:
      - my_theme/global-styling: false
```
* Configure which libraries will be used at `/admin/appearance/settings/YOUR_THEME`

## INSTALLATION

Install this module as any other Drupal module, see the documentation on
[Drupal.org](https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules).

## THANKS TO

* [Synetic](https://www.drupal.org/synetic) for providing time to work on this
  module,
* [Steven Buteneers](https://www.drupal.org/u/steven-buteneers), for developing this module.
