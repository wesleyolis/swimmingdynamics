// $Id: collapsiblock.js,v 1.10 2007/02/03 07:28:14 nedjo Exp $

Drupal.collapsiblockAutoAttach = function () {
  // Different themes may use different tags for the block title. If there are others used, add them to this array.
  var headTags = ['h2','h3'];
  $('div.block').each(function () {
    var id = this.id;
    var titleElt = null;
    // Ensure we have a collapsable content block element.
    for (var i in headTags) {
      if ($(headTags[i], this).length > 0) {
        var titleElt = $(headTags[i], this).get(0);
        break;
      }
    }
    if (titleElt) {
      // Status values: 1 = not collapsible, 2 = collapsible and expanded, 3 = collapsible and collapsed
      var status = Drupal.settings.collapsiblock[this.id] ? Drupal.settings.collapsiblock[this.id] : 2;
      if (status == 1) {
        return;
      }
      titleElt.target = $(this).find('div.content');
      $(titleElt)
        .addClass('collapsiblock')
        .click(function () {
          var st = $.cookie('collapsiblock-' + id);
          $.cookie('collapsiblock-' + id, st == 0 ? 1 : 0);
          $(this).toggleClass('collapsiblockCollapsed');
          $(this.target).slideToggle('slow');
        });
      if (status == 3 || $.cookie('collapsiblock-' + id) == 1) {
        $(titleElt).addClass('collapsiblockCollapsed');
        $(titleElt.target).slideUp();
      }
      else {
        $.cookie('collapsiblock-' + id, 0);
      }
    }
  });
}

// Global Killswitch
if (Drupal.jsEnabled) {
  $(document).ready(Drupal.collapsiblockAutoAttach);
}
