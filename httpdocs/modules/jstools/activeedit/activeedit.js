// $Id: activeedit.js,v 1.4 2007/03/06 22:03:09 nedjo Exp $

Drupal.activeeditAttach = function() {

  if (Drupal.settings.activeedit) {
    // Prevent duplicate attachment of the collapsible behavior.
    $('fieldset.collapsible').each(function() {
      $(this)
        .removeClass('collapsible')
        .addClass('activeedit-collapsible');
    });
    // Register autocompletes.
    $('input.autocomplete').each(function () {
      var autocompletes = Drupal.settings.activeedit.autocompletes;
      for (key in autocompletes) {
        if (this.value.indexOf(autocompletes[key]['#marker']) != -1) {
          // Find the input that this autocomplete is attached to.
          var input = $('#' + this.id.substr(0, this.id.length - 13))[0];
          // Register it as having an activeedit.
          input.activeedit = key;
        }
      }
    });

    // Process bar-type activeedit targets.
    var bars = Drupal.settings.activeedit.bars;
    for (key in bars) {
      $(bars[key]['#selector'] + ':not(.activeedit-processed)').each(function() {
        var children = Drupal.elementChildren(bars[key]);
        // If there are children, process them instead.
        if (children.length) {
          // Determine id.
          var form = $('form.activeedit-data', this).get(0);
          var id = $(form).find('input[@name=' + bars[key]['#id_field'] + ']').attr('value');
          // If no id, we don't have edit access.
          if (id) {
            // Replace the wildcard. The wildcard has been urlencoded.
            var target = bars[key]['#target'].replace('%2A', id);
            for (i in children) {
              $(bars[key][children[i]]['#selector'] + ':not(.activeedit-processed)', this).each(function() {
                // Above attempt to exclude by class [not(.activeedit-processed)] not working.
                if (this.className.indexOf('activeedit-processed') == -1) {
                  
                }
              });
            }
          }
        }
        else {
          // Not yet supported.
        }
      });
    }

    // Process link-type activeedit targets.
    var links = Drupal.settings.activeedit.links;
    for (key in links) {
      $('a[@href*=' + links[key]['#marker'] + ']').not('.activeedit-processed').each(function() {
        this.key = key;
        if ($(this).text() == links[key]['#text']) {
          $(this)
            .addClass('activeedit-processed')
            .click(function () {
              var uri = $(this).attr('href');
              if (uri.indexOf('#') > -1) {
                uri = uri.substr(0, uri.indexOf('#'));
              }
              this.uri = uri + (Drupal.settings.jstools.cleanurls ? '?' : '&') + 'activeedit_id=' + this.key + '&activeedit_type=links';
              Drupal.activeeditTrigger(this);
              return false;
            });
        }
      });
    }
    // Process element-type activeedit targets.
    var elements = Drupal.settings.activeedit.elements;
    for (key in elements) {
      $(elements[key]['#selector'] + ':not(.activeedit-processed)').each(function() {
        var children = Drupal.elementChildren(elements[key]);
        // If there are children, process them instead.
        if (children.length) {
          // Determine id.
          var form = $('form.activeedit-data', this).get(0);
          var id = $(form).find('input[@name=' + elements[key]['#id_field'] + ']').attr('value');
          // If no id, we don't have edit access.
          if (id) {
            // Replace the wildcard. The wildcard has been urlencoded.
            var target = elements[key]['#target'].replace('%2A', id);
            for (i in children) {
              $(elements[key][children[i]]['#selector'] + ':not(.activeedit-processed)', this).each(function() {
                // Above attempt to exclude by class [not(.activeedit-processed)] not working.
                // Don't attach to empty elements.
                if (this.className.indexOf('activeedit-processed') == -1 && ($(this).html() != '')) {
                  // If there is a required field missing, skip.
                  if (!elements[key][children[i]]['#require'] || $(form).find('input[@name=' + elements[key][children[i]]['#require'] + ']').attr('value') == 1) {
                    Drupal.activeeditAdd(this, target, children[i], elements[key][children[i]]['#title'], 'elements');
                  }
                }
              });
            }
          }
        }
        // Don't attach to empty elements.
        else if ($(this).html() != '') {
          Drupal.activeeditAdd(this, elements[key]['#target'], key, elements[key]['#title'], 'elements');
        }
      });
    }
  }
}

Drupal.activeeditAdd = function(elt, uri, key, title, type) {
  elt.uri = uri + (Drupal.settings.jstools.cleanurls ? '?' : '&') + 'activeedit_id=' + key + '&activeedit_type=' + type;
  var button = document.createElement('button');
  $(button)
    .append(document.createTextNode('Edit'))
    .attr('title', 'Click to change the ' + (title ? title : key.replace('_', ' ')) + '.')
    .addClass('activeedit-button')
    .mouseover(function() {
      $(this).addClass('activeedit-active');
    })
    .mouseout(function() {
      $(this).removeClass('activeedit-active');
    })
    .click(function() {
      Drupal.activeeditTrigger(elt);
      return false;
    }
  );

  elt.button = button;
  $(elt)
    .append(button)
    .mouseover(function() {
      $(this.button).addClass('activeedit-opaque');
    })
    .mouseout(function() {
      $(this.button).removeClass('activeedit-opaque');
    })
    .addClass('activeedit-processed');
}

Drupal.activeeditTrigger = function(elt) {
  // Cancel any other activeedit process.
  $('#activeedit-cancel').click();
  $('#activeedit-success').click();

  // Insert progressbar.
  var progress = new Drupal.progressBar('activeeditprogress');
  progress.setProgress(-1, 'Fetching form');
  var progressElt = progress.element;
  $(progressElt).css({
    width: '250px',
    height: '15px',
    paddingTop: '10px',
    display: 'none'
  });

  $(elt)
    .before(progressElt)
    .slideUp('slow');

  $(progressElt).fadeIn('slow');
  $.ajax({
    type: 'GET',
    url: elt.uri,
    success: function(response){
      $(progressElt).slideUp('slow');
      response = Drupal.parseJson(response);
      // Create a dummy to hold the place of the loaded window. This is needed to remove the actual form being loaded
      // from this place in the DOM, in case we're already in a form.
      $(elt).after('<div id="activeedit-dummy"></div>');
      $('body').append('<div id="activeedit-window"><div id="activeedit-message">' + (response.data.message ? response.data.message : '') + '</div>' +response.data.content + '<button id="activeedit-cancel">Cancel</button></div>');
      // Attach collapse behavior to newly-loaded form content.
      Drupal.collapseAutoAttach();
      $('#activeedit-cancel').click(function() {
        $(progressElt).remove();
        $('#activeedit-dummy').remove();
        $('#activeedit-window').remove();
        $(elt).slideDown('slow');
      });
      Drupal.activeeditShowWindow();
      var form = $("#activeedit-window form").get(0);
      var activeeditInput = document.createElement('input');
      $(activeeditInput)
        .attr('type', 'hidden')
        .attr('name', 'activeedit_submit')
        .attr('value', '1');
      $(form).append(activeeditInput);
      var edit = new Drupal.activeedit(elt, form, progress);
    }
  });
}

/**
 * Show the activeedit window.
 */
Drupal.activeeditShowWindow = function () {
  var fudge = 20;
  $('#activeedit-dummy')
    .height($('#activeedit-window').height() + fudge + 'px')
    .width($('#activeedit-window').width() + 'px')
    .slideDown('slow', function() {
      var pos = Drupal.absolutePosition(this);
      Drupal.collapseScrollIntoView(this);
      // jQuery removes the height and width on finishing slideDown.
      // Restore them.
      $(this)
        .height($('#activeedit-window').height() + fudge + 'px')
        .width($('#activeedit-window').width() + 'px');
      $('#activeedit-window')
        .css({
          left: pos.x + 'px',
          top: pos.y + 'px',
          display: 'none',
          visibility: 'visible'
        })
        .fadeIn('slow');
    });
}

/**
 * Hide the activeedit window.
 *
 * We manipulate visibility rather than just using the .show() or similar
 * methods because we need later access to the dimensions of the window
 * while it's invisible in order to correctly size the dummy placeholder.
 */
Drupal.activeeditHideWindow = function () {
  $('#activeedit-window').fadeOut('slow', function() {
    $('#activeedit-dummy').slideUp('slow');
    $(this).css({
      visibility: 'hidden'
    });
    $(this).show();
  });
}

/**
 * activeedit object.
 */
Drupal.activeedit = function(elt, form, progress) {
  this.elt = elt;
  this.progress = progress;
  Drupal.redirectFormSubmit(elt.uri, form, this);
}

/**
 * Handler for the form redirection submission.
 */
Drupal.activeedit.prototype.onsubmit = function () {
  Drupal.activeeditHideWindow();
  this.progress.setProgress(-1, 'Submitting form');
  $(this.progress.element).fadeIn('slow');
}

/**
 * Handler for the form redirection completion.
 */
Drupal.activeedit.prototype.oncomplete = function (data) {
  $(this.progress.element).fadeOut();
  var elt = this.elt;
  if (data.error) {
    $('#activeedit-window')
      .find('#activeedit-message')
      .html(data.message);
    Drupal.activeeditShowWindow();
    return;
  }
  $(this.progress.element).remove();
  this.progress = null;
  if (data.content) {
    $("#activeedit-dummy").remove();
    $("#activeedit-window").remove();
    switch (data.placement) {
      case 'html':
        $(elt)
          .removeClass('activeedit-processed')
          .html(data.content)
          .slideDown('slow');
        // Reattach the behavior.
        Drupal.activeeditAttach();
        break;
      case 'after':
        $(elt)
          .after(data.content)
          .slideDown('slow');
        break;
    }
  }
  else {
    $('#activeedit-window').html((data.message ? data.message : 'The record you added will be visible the next time the page is loaded.') + '<button id="activeedit-success">ok</button>');
    $('#activeedit-success').click(function() {
        $("#activeedit-window").slideUp('slow', function() {
          $("#activeedit-dummy").remove();
          $("#activeedit-window").remove();
          $(elt).slideDown('slow')
        });
      });
    Drupal.activeeditShowWindow();
  }
}

/**
 * Handler for the form redirection error.
 */
Drupal.activeedit.prototype.onerror = function (error) {
  alert('An error occurred: ' + error);
  $(this.progress.element)
    .fadeOut();
  // Call the cancel routine.
  $("#activeedit-cancel").click();
}

if (Drupal.jsEnabled) {
  $(document).ready(Drupal.activeeditAttach);
}

Drupal.collapseAutoAttach = function() {
  $('fieldset.collapsible > legend').each(function() {
    var fieldset = $(this.parentNode);
    // Expand if there are errors inside
    if ($('input.error, textarea.error, select.error', fieldset).size() > 0) {
      fieldset.removeClass('collapsed');
    }

    // Turn the legend into a clickable link and wrap the contents of the fieldset
    // in a div for easier animation
    var text = this.innerHTML;
    $(this).empty().append($('<a href="#">'+ text +'</a>').click(function() {
      var fieldset = $(this).parents('fieldset:first')[0];
      // Don't animate multiple times
      if (!fieldset.animating) {
        fieldset.animating = true;
        Drupal.toggleFieldset(fieldset);
      }
      return false;
    })).after($('<div class="fieldset-wrapper"></div>').append(fieldset.children(':not(legend)')));
  });
}