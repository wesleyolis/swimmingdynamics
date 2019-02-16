// $Id: jstools.js,v 1.14 2007/03/14 19:37:30 nedjo Exp $

Drupal.preventSelect = function (elt) {
  // IE hack to prevent selection of the text when users click.
  if (document.onselectstart) {
    elt.onselectstart = function () {
      return false;
    }
  }
  else {
    $(elt).mousedown(function () {
      return false;
    });
  }
}

/**
 * Returns an event's source element.
 */
Drupal.getTarget = function (e) {	
  if (!e) e = window.event;	
  var target = e.target ? e.target : e.srcElement
  if (!target) return null;
  if (target.nodeType == 3) target = target.parentNode; // safari weirdness		
  if (target.tagName.toUpperCase() == 'LABEL' && e.type == 'click') { 
    // When clicking a label, firefox fires the input onclick event
    // but the label remains the source of the event. In Opera and IE 
    // the source of the event is the input element.
    if (target.getAttribute('for')) {
      target = document.getElementById(target.getAttribute('for'));
    }
  }
  return target;
}

Drupal.url = function (path, query, fragment) {
  query = query ? query : '';
  fragment = fragment ? fragment : '';
  var base = Drupal.settings.jstools.basePath;
  if (!Drupal.settings.jstools.cleanurls) {
    if (query) {
      return base + '?q=' + path + '&' + query + fragment;
    }
    else {
      return base + '?q=' + path + '&' + fragment;
    }
  }
  else {
    if (query) {
      return base + path + '?' + query + fragment;
    }
    else {
      return base + path + fragment;
    }
  }
}

Drupal.getPath = function (href) {
  // 3 is the length of the '?q=' added to the url without clean urls.
  href = href.substring(Drupal.settings.jstools.basePath.length + (Drupal.settings.jstools.cleanurls ? 0 : 3), href.length);
  var chars = ['#', '?', '&'];
  for (i in chars) {
    if (href.indexOf(chars[i]) > -1) {
      href = href.substr(0, href.indexOf(chars[i]));
    }
  }
  return href;
}

/**
 * Add a segment to the beginning of a path.
 */
Drupal.prependPath = function (href, segment) {
  // 3 is the length of the '?q=' added to the url without clean urls.
  var baseLength = Drupal.settings.jstools.basePath.length + (Drupal.settings.jstools.cleanurls ? 0 : 3);
  var base = href.substring(0, baseLength);
  return base + segment + '/' + href.substring(baseLength, href.length);
}

/**
 * Redirects a form submission to a hidden iframe and displays the result
 * in a given wrapper. The iframe should contain a call to
 * window.parent.iframeHandler() after submission.
 */
Drupal.redirectFormSubmit = function (uri, form, handler) {
  $(form).submit(function() {
    // Create target iframe
    Drupal.createIframe();

    // Prepare variables for use in anonymous function.
    var form = this;

    // Redirect form submission to iframe
    this.action = uri;
    this.target = 'redirect-target';

    handler.onsubmit();

    // Set iframe handler for later
    window.iframeHandler = function () {
      var iframe = $('#redirect-target').get(0);

      // Get response from iframe body
      try {
        response = (iframe.contentWindow || iframe.contentDocument || iframe).document.body.innerHTML;
        // Firefox 1.0.x hack: Remove (corrupted) control characters
        response = response.replace(/[\f\n\r\t]/g, ' ');
        if (window.opera) {
          // Opera-hack: it returns innerHTML sanitized.
          response = response.replace(/&quot;/g, '"');
        }
      }
      catch (e) {
        response = null;
      }

      response = Drupal.parseJson(response);
      // Check response code
      if (response.status == 0) {
        handler.onerror(response.data);
        return;
      }
      handler.oncomplete(response.data);

      return true;
    }

    return true;
  });
}

/**
 * Scroll to a given element's vertical page position.
 */
Drupal.scrollTo = function(el) {
  var pos = Drupal.absolutePosition(el);
  window.scrollTo(0, pos.y);
}

Drupal.elementChildren = function (element) {
  var children = [];
  for (i in element) {
    if (i.substr(0, 1) != '#') {
      children[children.length] = i;
    }
  }
  return children;
}

Drupal.elementProperties = function (element) {
  var properties = [];
  for (i in element) {
    if (i.substr(0, 1) == '#') {
      properties[properties.length] = i;
    }
  }
  return properties;
}

jQuery.extend({
  behaviors: [],
  registerBehavior: function(attachFunction, ready) {
    // Default to true.
    var ready = (ready == null) ? true : ready;
    jQuery.behaviors.push(attachFunction);
    if (ready) {
      jQuery.readyList.push(attachFunction);
    }
  }
});

jQuery.fn.attachBehaviors = function() {
  var elt = this;
  if (jQuery.behaviors) {
    // Execute all of them.
    jQuery.each(jQuery.behaviors, function(){
      this.apply(elt);
    });
  }
};
