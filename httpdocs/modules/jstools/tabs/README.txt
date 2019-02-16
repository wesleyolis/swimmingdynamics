Drupal tabs.module README.txt
==============================================================================

Created as part of a project sponsored by CivicSpace Labs, this module
provides methods for client-side tabbed pages, using the jQuery tabs
plugin by Klaus Hartl, http://stilbuero.de/tabs.

NOTE: These instructions still need updating for 5.0.

Requirements
------------------------------------------------------------------------------
This module is written for Drupal 5.0+.


Installation
------------------------------------------------------------------------------
Create a directory modules/tabs and copy all the module's files into
it. Enable the module via the administer modules page.


Testing
------------------------------------------------------------------------------
To test the module, navigate to the page tabs/example.


Developer usage
-----------------------------------------------------------------------------

Tabs are generated through forms-api-style arrays using two element types
defined in tabs.module: 'tabset' (a set of tabs) and 'tabpage' (a tab page on
a set of tabs).

Tabs can be returned as part of normal forms, or can be 'manually' rendered
through a call to the function tabs_render().

Sample usage:

  $form = array();

  $form['example1'] = array(
    '#type' => 'tabset',
  );
  $form['example1']['tab1'] = array(
    '#type' => 'tabpage',
    '#title' => t('One'),
    '#content' => t('First tab content.'),
  );
  $form['example1']['tab2'] = array(
    '#type' => 'tabpage',
    '#title' => t('Two'),
    '#content' => t('Second tab content.'),
  );
  $form['example1']['tab3'] = array(
    '#type' => 'tabpage',
    '#title' => t('Three'),
    '#content' => t('Third tab content.'),
  );

  return tabs_render($form);