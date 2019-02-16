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
