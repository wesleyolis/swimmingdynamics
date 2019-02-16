// $Id: activemenu.js,v 1.21 2007/01/23 22:07:22 nedjo Exp $

Drupal.activemenuAutoAttach = function () {
  // The elements supported. Each can designate a different uri.
  var menus = Drupal.settings.activemenu;

  for (var menu in menus) {
    $(menu + ' li.expanded:not(.activemenu-processed)').each(function () {
      Drupal.preventSelect(this);
      $(this)
        .click(function (e) {
          // Only toggle if this is the element that was clicked.
          // Otherwise, a parent li element might be toggled too.
          if (this == Drupal.getTarget(e)) {
            $(this)
              .toggleClass('collapsed')
              .toggleClass('expanded')
              .find('ul')
              .slideToggle();
          }
        })
        .addClass('activemenu-processed')
        .find('ul')
        .slideDown('fast', function () {
          $(this).css(
            {height: ''}
          );
        });
      });
    $(menu + ' li.collapsed:not(.activemenu-processed)').each(function() {
      if ($(this).children('ul').length > 0) {
        return;
      }
      var path = Drupal.getPath(this.firstChild.getAttribute('href', 2));
      var url = Drupal.url(menus[menu]);
      var elt = this;
      Drupal.preventSelect(this);
      $(this)
        .click(function (e) {
          var offset = Drupal.mousePosition(e).x - Drupal.absolutePosition(this).x;
          // Determine if we are in the selection area.
          if (offset < (0 + parseInt($(this).css('padding-left')))) {
            // Ajax GET request for autocompletion
            $.ajax({
              type: 'POST',
              url: url,
              data: 'path=' + path,
              success: function (data) {
                if ($(elt).children('ul').length > 0) {
                  return;
                }
                data = Drupal.parseJson(data);
                $(elt)
                  .append(data.content)
                  .removeClass('collapsed')
                  .addClass('expanded')
                  .unclick()
                  .click(function (e) {
                    // Only toggle if this is the element that was clicked.
                    // Otherwise, a parent li element might be toggled too.
                    if (this == Drupal.getTarget(e)) {
                      $(this)
                        .toggleClass('collapsed')
                        .toggleClass('expanded')
                        .find('ul')
                        .slideToggle();
                    }
                  })
                  .find('ul')
                  .slideDown();
                Drupal.activemenuAutoAttach();
              },
              error: function (xmlhttp) {
                alert('An HTTP error '+ xmlhttp.status +' occured.\n' + url);
              }
            });
            return false;
          }
        })
        .addClass('activemenu-processed');
    });
  }
}

// Global Killswitch
if (Drupal.jsEnabled) {
  $(document).ready(Drupal.activemenuAutoAttach);
}
