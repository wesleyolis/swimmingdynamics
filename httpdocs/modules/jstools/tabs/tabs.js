Drupal.tabsAttach = function() {
  $('.drupal-tabs')
    .addClass('tabs')
    .tabs({
      onShow: Drupal.tabsAddClassesCallback()
    })
    .show()
    .find('ul.anchors')
    .addClass('tabs')
    .addClass('primary');
  Drupal.tabsAddClasses();
}

Drupal.tabsAddClassesCallback = function() {
  return function() {
    Drupal.tabsAddClasses();
  }
}

Drupal.tabsAddClasses = function() {
  $('ul.anchors.tabs.primary')
    .find('.active')
    .removeClass('active')
    .end()
    .find('.tabs-selected')
    .addClass('active');
}

if (Drupal.jsEnabled) {
  $(document).ready(Drupal.tabsAttach);
}