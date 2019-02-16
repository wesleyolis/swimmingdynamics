// $Id: googleanalytics.js,v 1.3.2.8 2009/03/04 07:25:47 hass Exp $

Drupal.behaviors.gaTrackerAttach = function(context) {

  // Attach onclick event to all links.
  $('a', context).click( function() {
    var ga = Drupal.settings.googleanalytics;
    // Expression to check for absolute internal links.
    var isInternal = new RegExp("^(https?):\/\/" + window.location.host, "i");
    // Expression to check for special links like gotwo.module /go/* links.
    var isInternalSpecial = new RegExp("(\/go\/.*)$", "i");
    // Expression to check for download links.
    var isDownload = new RegExp("\\.(" + ga.trackDownloadExtensions + ")$", "i");

    try {
      // Is the clicked URL internal?
      if (isInternal.test(this.href)) {
        // Is download tracking activated and the file extension configured for download tracking?
        if (ga.trackDownload && isDownload.test(this.href)) {
          // Download link clicked.
          var extension = isDownload.exec(this.href);
          pageTracker._trackEvent("Downloads", extension[1].toUpperCase(), this.href.replace(isInternal, ''));
        }
        else if (isInternalSpecial.test(this.href)) {
          // Keep the internal URL for Google Analytics website overlay intact.
          pageTracker._trackPageview(this.href.replace(isInternal, ''));
        }
      }
      else {
        if (ga.trackMailto && $(this).is("a[href^=mailto:]")) {
          // Mailto link clicked.
          pageTracker._trackEvent("Mails", "Click", this.href.substring(7));
        }
        else if (ga.trackOutgoing) {
          // External link clicked.
          pageTracker._trackEvent("Outgoing links", "Click", this.href);
        }
      }
    } catch(err) {}
  });
}
;
// $Id: wysiwyg.js,v 1.15.2.2 2010/02/13 23:58:41 sun Exp $
(function($) {

/**
 * Initialize editor libraries.
 *
 * Some editors need to be initialized before the DOM is fully loaded. The
 * init hook gives them a chance to do so.
 */
Drupal.wysiwygInit = function() {
  // This breaks in Konqueror. Prevent it from running.
  if (/KDE/.test(navigator.vendor)) {
    return;
  }

  jQuery.each(Drupal.wysiwyg.editor.init, function(editor) {
    // Clone, so original settings are not overwritten.
    this(jQuery.extend(true, {}, Drupal.settings.wysiwyg.configs[editor]));
  });
};

/**
 * Attach editors to input formats and target elements (f.e. textareas).
 *
 * This behavior searches for input format selectors and formatting guidelines
 * that have been preprocessed by Wysiwyg API. All CSS classes of those elements
 * with the prefix 'wysiwyg-' are parsed into input format parameters, defining
 * the input format, configured editor, target element id, and variable other
 * properties, which are passed to the attach/detach hooks of the corresponding
 * editor.
 *
 * Furthermore, an "enable/disable rich-text" toggle link is added after the
 * target element to allow users to alter its contents in plain text.
 *
 * This is executed once, while editor attach/detach hooks can be invoked
 * multiple times.
 *
 * @param context
 *   A DOM element, supplied by Drupal.attachBehaviors().
 */
Drupal.behaviors.attachWysiwyg = function(context) {
  // This breaks in Konqueror. Prevent it from running.
  if (/KDE/.test(navigator.vendor)) {
    return;
  }

  $('.wysiwyg:not(.wysiwyg-processed)', context).each(function() {
    var params = Drupal.wysiwyg.getParams(this);
    var $this = $(this).addClass('wysiwyg-processed');
    // Directly attach this editor, if the input format is enabled or there is
    // only one input format at all.
    if (($this.is(':input') && $this.is(':checked')) || $this.is('div')) {
      Drupal.wysiwygAttach(context, params);
    }
    // Attach onChange handlers to input format selector elements.
    if ($this.is(':input')) {
      $this.change(function() {
        // If not disabled, detach the current and attach a new editor.
        Drupal.wysiwygDetach(context, params);
        Drupal.wysiwygAttach(context, params);
      });
      // IE triggers onChange after blur only.
      if ($.browser.msie) {
        $this.click(function () {
          this.blur();
        });
      }
    }
    // Detach any editor when the containing form is submitted.
    $('#' + params.field).parents('form').submit(function () {
      Drupal.wysiwygDetach(context, params);
    });
  });
};

/**
 * Attach an editor to a target element.
 *
 * This tests whether the passed in editor implements the attach hook and
 * invokes it if available. Editor profile settings are cloned first, so they
 * cannot be overridden. After attaching the editor, the toggle link is shown
 * again, except in case we are attaching no editor.
 *
 * @param context
 *   A DOM element, supplied by Drupal.attachBehaviors().
 * @param params
 *   An object containing input format parameters.
 */
Drupal.wysiwygAttach = function(context, params) {
  if (typeof Drupal.wysiwyg.editor.attach[params.editor] == 'function') {
    // (Re-)initialize field instance.
    Drupal.wysiwyg.instances[params.field] = {};
    // Provide all input format parameters to editor instance.
    jQuery.extend(Drupal.wysiwyg.instances[params.field], params);
    // Provide editor callbacks for plugins, if available.
    if (typeof Drupal.wysiwyg.editor.instance[params.editor] == 'object') {
      jQuery.extend(Drupal.wysiwyg.instances[params.field], Drupal.wysiwyg.editor.instance[params.editor]);
    }
    // Store this field id, so (external) plugins can use it.
    // @todo Wrong point in time. Probably can only supported by editors which
    //   support an onFocus() or similar event.
    Drupal.wysiwyg.activeId = params.field;
    // Attach or update toggle link, if enabled.
    if (params.toggle) {
      Drupal.wysiwygAttachToggleLink(context, params);
    }
    // Otherwise, ensure that toggle link is hidden.
    else {
      $('#wysiwyg-toggle-' + params.field).hide();
    }
    // Attach editor, if enabled by default or last state was enabled.
    if (params.status) {
      Drupal.wysiwyg.editor.attach[params.editor](context, params, (Drupal.settings.wysiwyg.configs[params.editor] ? jQuery.extend(true, {}, Drupal.settings.wysiwyg.configs[params.editor][params.format]) : {}));
    }
    // Otherwise, attach default behaviors.
    else {
      Drupal.wysiwyg.editor.attach.none(context, params);
      Drupal.wysiwyg.instances[params.field].editor = 'none';
    }
  }
};

/**
 * Detach all editors from a target element.
 *
 * @param context
 *   A DOM element, supplied by Drupal.attachBehaviors().
 * @param params
 *   An object containing input format parameters.
 */
Drupal.wysiwygDetach = function(context, params) {
  var editor = Drupal.wysiwyg.instances[params.field].editor;
  if (jQuery.isFunction(Drupal.wysiwyg.editor.detach[editor])) {
    Drupal.wysiwyg.editor.detach[editor](context, params);
  }
};

/**
 * Append or update an editor toggle link to a target element.
 *
 * @param context
 *   A DOM element, supplied by Drupal.attachBehaviors().
 * @param params
 *   An object containing input format parameters.
 */
Drupal.wysiwygAttachToggleLink = function(context, params) {
  if (!$('#wysiwyg-toggle-' + params.field).length) {
    var text = document.createTextNode(params.status ? Drupal.settings.wysiwyg.disable : Drupal.settings.wysiwyg.enable);
    var a = document.createElement('a');
    $(a).attr({ id: 'wysiwyg-toggle-' + params.field, href: 'javascript:void(0);' }).append(text);
    var div = document.createElement('div');
    $(div).addClass('wysiwyg-toggle-wrapper').append(a);
    $('#' + params.field).after(div);
  }
  $('#wysiwyg-toggle-' + params.field)
    .html(params.status ? Drupal.settings.wysiwyg.disable : Drupal.settings.wysiwyg.enable).show()
    .unbind('click').click(function() {
      if (params.status) {
        // Detach current editor.
        params.status = false;
        Drupal.wysiwygDetach(context, params);
        // After disabling the editor, re-attach default behaviors.
        // @todo We HAVE TO invoke Drupal.wysiwygAttach() here.
        Drupal.wysiwyg.editor.attach.none(context, params);
        Drupal.wysiwyg.instances[params.field] = Drupal.wysiwyg.editor.instance.none;
        Drupal.wysiwyg.instances[params.field].editor = 'none';
        $(this).html(Drupal.settings.wysiwyg.enable).blur();
      }
      else {
        // Before enabling the editor, detach default behaviors.
        Drupal.wysiwyg.editor.detach.none(context, params);
        // Attach new editor using parameters of the currently selected input format.
        Drupal.wysiwyg.getParams($('.wysiwyg-field-' + params.field + ':checked, div.wysiwyg-field-' + params.field, context).get(0), params);
        params.status = true;
        Drupal.wysiwygAttach(context, params);
        $(this).html(Drupal.settings.wysiwyg.disable).blur();
      }
    });
  // Hide toggle link in case no editor is attached.
  if (params.editor == 'none') {
    $('#wysiwyg-toggle-' + params.field).hide();
  }
};

/**
 * Parse the CSS classes of an input format DOM element into parameters.
 *
 * Syntax for CSS classes is "wysiwyg-name-value".
 *
 * @param element
 *   An input format DOM element containing CSS classes to parse.
 * @param params
 *   (optional) An object containing input format parameters to update.
 */
Drupal.wysiwyg.getParams = function(element, params) {
  var classes = element.className.split(' ');
  var params = params || {};
  for (var i in classes) {
    if (classes[i].substr(0, 8) == 'wysiwyg-') {
      var parts = classes[i].split('-');
      var value = parts.slice(2).join('-');
      params[parts[1]] = value;
    }
  }
  // Convert format id into string.
  params.format = 'format' + params.format;
  // Convert numeric values.
  params.status = parseInt(params.status, 10);
  params.toggle = parseInt(params.toggle, 10);
  params.resizable = parseInt(params.resizable, 10);
  return params;
};

/**
 * Allow certain editor libraries to initialize before the DOM is loaded.
 */
Drupal.wysiwygInit();

})(jQuery);
;
var xmlHttp


function GetXmlHttpObject(handler)
{ 
var objXmlHttp=null
if (navigator.userAgent.indexOf("Opera")>=0)
   {
    alert("This example doesn't work in Opera") 
    return  
   }
if (navigator.userAgent.indexOf("MSIE")>=0)
   { 
   var strName="Msxml2.XMLHTTP"
   if (navigator.appVersion.indexOf("MSIE 5.5")>=0)
      {
      strName="Microsoft.XMLHTTP"
      } 
   try
      { 
      objXmlHttp=new ActiveXObject(strName)
      objXmlHttp.onreadystatechange=handler 
      return objXmlHttp
      } 
   catch(e)
      { 
      alert("Error. Scripting for ActiveX might be disabled") 
      return 
      } 
    } 
if (navigator.userAgent.indexOf("Mozilla")>=0)
   {
   objXmlHttp=new XMLHttpRequest()
   objXmlHttp.onload=handler
   objXmlHttp.onerror=handler 
   return objXmlHttp
   }
} ;
var rec = 0,contents =null,xmlHttp=null;
var  hover = false;


var url = document.getElementById('record_url').value;
document.getElementById('record_breakers').onmouseover=function(){;hover=true};
document.getElementById('record_breakers').onmouseout=function(){hover=false};
setTimeout("Refresh()",200);

function stateChanged() 
{ 
	var contents = null;
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
   { 
   		if(contents==null)
	   contents = xmlHttp.responseText
	   if(contents!='')
	   {
	   		if(!hover)
		{
			document.getElementById('record_breakers').innerHTML=contents;
			rec++;
			contents=null;
			setTimeout("Refresh()",5000);
		}else{setTimeout("stateChanged()",500);
}
			
		}
		else
		{
		if(rec>0)
		{
			rec=0;
		
			Refresh();
		}
		}
		
		
   } 
}   

function Refresh()
{
		try {
				xmlHttp = new XMLHttpRequest ();
			}
			catch (e){
				try {
					xmlHttp = new ActiveXObject ('Msxml2.XMLHTTP')
				}
				catch (e){
					xmlHttp = new ActiveXObject ('Microsoft.XMLHTTP');
				}
			}
		 xmlHttp.onreadystatechange=stateChanged;
		
	  	 xmlHttp.open("GET",url+'&set='+rec , true);
	   	 xmlHttp.send(null);	
	
}


;
