// $Id: dynamicload.js,v 1.19 2007/03/12 02:08:06 nedjo Exp $

Drupal.dynamicloadAutoAttach = function () {
  for (var key in Drupal.settings.dynamicload.settings) {
    // Only proceed if both source and target elements exist.
    var selector = Drupal.settings.dynamicload.settings[key].selector;
    if (Drupal.settings.dynamicload.settings[key].apply && $(selector).length && ((Drupal.settings.dynamicload.settings[key].target == '') || $(Drupal.settings.dynamicload.settings[key].target).length)) {
      // Apply to elements that are themselves 'a' elements or to their descendent 'a' elements.
      $(selector).filter('a').add(selector + ' a').each(function () {
        Drupal.dynamicload(this, {'target': Drupal.settings.dynamicload.settings[key].target == '' ? false : $(Drupal.settings.dynamicload.settings[key].target).get(0)});
      });
    }
    if (Drupal.settings.dynamicload.settings[key].refresh && $(selector).length) {
      $(selector).each(function () {
        var timeoutId = window.setTimeout('Drupal.dynamicloadBlockTimeout("' + Drupal.settings.dynamicload.settings[key].module + '", ' + Drupal.settings.dynamicload.settings[key].delta + ', "' + Drupal.settings.dynamicload.settings[key].selector + '")', Drupal.settings.dynamicload.settings[key].refresh_interval);
        $(window).unload(function () {clearTimeout(timeoutId);});
      });
    }
  }
  $('a.dynamicload, .dynamicload a').each(function () {
    Drupal.dynamicload(this);
  });
}

Drupal.dynamicloadPager = function () {
  $('div.pager a').each(function () {
    var target = $(Drupal.settings.dynamicload.content).length ? $(Drupal.settings.dynamicload.content).get(0) : $(this).prev();
    Drupal.dynamicload(this, {'target': target, 'success': Drupal.dynamicloadPager});
  });
}

Drupal.dynamicload = function (elt, settings) {
  settings = jQuery.extend({
     useClick: true,
     target: elt,
     success: null,
     show: true
  }, settings);

  // The second argument is to get around an IE bug of returning absolute URLs.
  var href = elt.getAttribute('href', 2);
  // Only process internal site links.
  if (href.indexOf(Drupal.settings.jstools.basePath) == -1) {
    return;
  }
  // Prepend to the path.
  href = Drupal.prependPath(href, 'dynamicload/js');
  var load = function () {
    var cachedContent = $(settings.target).html();
    // Insert progressbar.
    var progress = new Drupal.progressBar('dynamicloadprogress');
    progress.setProgress(-1, 'Fetching page');
    var progressElt = progress.element;
    $(progressElt).css({
      width: '250px',
      height: '15px',
      paddingTop: '10px'
    });
    $(settings.target)
       .html('')
       .append(progressElt);
    $.ajax({
      type: 'GET',
      data: 'target=' + (settings.target.id ? settings.target.id : '0'),
      url: href,
      success: function(response){
        $(progressElt).remove();
        progress = null;
        response = Drupal.parseJson(response);
        if (response.result) {
          if (settings.show) {
            $(settings.target)
              .hide()
              .html(response.content)
              .slideDown('slow');
          }
          else {
            $(settings.target).html(response.content);
          }
        }
        else {
          var message = response.message ? response.message : 'Unable to load page.';
          alert(message);
          $(settings.target)
            .html(cachedContent);
        }
        if (settings.success) {
          settings.success(settings);
        }
      }
    });
    return false;
  };
  if (settings.useClick) {
    $(elt).click(load());
  }
  else {
    load();
  }
}

Drupal.dynamicloadBlockTimeout = function (module, delta, selector) {
  var timeoutId = window.setTimeout('Drupal.dynamicloadBlockTimeout("' + module + '", ' + delta + ', "' + selector + '")', Drupal.settings.dynamicload.settings[module + '_' + delta].refresh_interval);
  $(window).unload(function () {clearTimeout(timeoutId);});
  Drupal.dynamicloadLoadBlock(module, delta, selector);
}

Drupal.dynamicloadLoadBlock = function (module, delta, selector) {
  $.ajax({
    type: 'GET',
    url: Drupal.url('dynamicload/block/' + module + '/' + delta),
    success: function(response){
      response = Drupal.parseJson(response);
      if (response.result) {
        $(selector)
          .after(response.content)
          .remove();
      }
    }
  });
}

// Global Killswitch
if (Drupal.jsEnabled) {
  $(document).ready(Drupal.dynamicloadAutoAttach);
}