var cookiename='swimdynamics_browser_support1';
var redirect_download = false;
	
	cookie_fields = document.cookie.split(';');
		for(c=0;c<cookie_fields.length;c++)
			if(cookie_fields[c].indexOf(cookiename)>=0)
				break;
		if(c<cookie_fields.length)
		{
			
			//allready triggered to day.
		}
		else
		{
			days = checkVersion();
			//now re_write the cookie
			expire_date = new Date();
			
			expire_date.setDate(expire_date.getDate()+days);
			updated_cookie =cookiename+"=";	
			updated_cookie +=";expires="+(expire_date.toGMTString()) + "; ";
			updated_cookie += "path=/;";
			document.cookie = updated_cookie;
			if(redirect_download == true)
			window.location="http://wp.rsa.swimdynamics.co.za/node/13";
		}
		//eleminate displayed adevertisments
		
		
		
		


function getInternetExplorerVersion()
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
{
  var rv = -1; // Return value assumes failure.
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}
function checkVersion()
{
  var ver = getInternetExplorerVersion();

  if ( ver > -1 )
  {
    if ( ver < 8.0 ) 
	{
		
	alert("You browser version is now blocked and out of date aswell as being a security vulnerability to everyone, yourself and the world wide web.\nYou are going to be redirected to a page to upgrade your browser to the latest and safest browser!!\nYour current browser is now being block from accessing this site and other web sites on the internet and banks from the 20 march until you upgrade your browser which is free.\nYou are going to be redirected to download a better browser that supports more HTML5 features and functions.\n\nThis will greatly enhance your web browsing experience.Internet Explorer 9 - Microsoft latest browser when released and can only be run on windows 7,8.\n\nThis means that you must change your internet browser to chrome or firefox, safar to experience new web functionality (much cheaper and free) or upgrade to windows 7,8.\n\nBy doing this you allow the web to bring you a richer web experience.\n\ newer browser also support GPU acceleration and 3D graphics on older setups form which you shall also benefit.\n\n You may click the back button to return to the site, after downloading an updated browser.");
	redirect_download = true;
	return 0;
	}
	else
	{
		if ( ver < 8.0 ) 
		{
		alert("You are going to be redirected to download a better browser that supports more HTML5 features and functions.\n\nThis will greatly enhance your web browsing experience.Internet Explorer 9 - Microsoft latest browser when released and can only be run on windows 7,8.\n\nThis means that you must change your internet browser to chrome or firefox, safar to experience new web functionality (much cheaper and free) or upgrade to windows 7,8.\n\nBy doing this you allow the web to bring you a richer web experience.\n\ newer browser also support GPU acceleration and 3D graphics on older setups form which you shall also benefit.\n\n You may click the back button to return to the site, after downloading an updated browser.");
		redirect_download = true;
		return 16;
		}
	}
  }
  
}