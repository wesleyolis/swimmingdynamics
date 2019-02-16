var xmlHttpad = null;
var ad_url=null;
var ad_element =null;
if(!(ad_time>0))
var ad_time = 4;
if(!(ad_cookie_time>0))
var ad_cookie_time = 30;
function get_advert()
{
	
		
		if(xmlHttpad == null)
		{
		try {
				xmlHttpad = new XMLHttpRequest ();
			}
			catch (e){
				try {
					xmlHttpad = new ActiveXObject ('Msxml2.XMLHTTP')
				}
				catch (e){
					xmlHttpad = new ActiveXObject ('Microsoft.XMLHTTP');
				}
			}
		}
		 xmlHttpad.onreadystatechange=stateChanged;
		
	  	 xmlHttpad.open("GET",ad_url , true);
	   	 xmlHttpad.send(null);	
		
	
}

function stateChanged() 
{ 
	
	var contents = null;
if (xmlHttpad.readyState==4 || xmlHttpad.readyState=="complete")
   { 
   	   contents = xmlHttpad.responseText;
	   if(contents!='')
	   {
		//document.getElementbyId(ad_element).innerHTML = contents;
		
	   document.getElementById(ad_element+"_content").innerHTML=contents;
	   document.getElementById(ad_element).style.display='block';
	   //start advertiment time out
	  //setTimeout("document.getElementById(ad_element).style.display='none';",ad_time*1000);
           splash_add_count_down( ad_time );

	   }


   } 
}

function splash_add_count_down( sec )
{
    if( sec > 0 )
    {
        document.getElementById(ad_element+"_butons").innerHTML = 'Close Advertisment in ( ' + sec + 's ) ';
        setTimeout(function(){splash_add_count_down( sec - 1 )},1000);
    }
    else
    {
        document.getElementById(ad_element+"_butons").innerHTML ="<a onclick=\"document.getElementById(ad_element).style.display='none';\">Close Advertisment</a>";

    }
}

var cookiename="swimdynamics_ad";

function ajax_splash_add(ad_urls,element)
{
	ad_display = new Array();

		
		cookie_fields = document.cookie.split(';')
        c = cookie_fields.length;
		for(c1=0;c1<cookie_fields.length;c1++)
        {
            if(cookie_fields[c1].indexOf(cookiename+"_displayedtoday")>=0)
            {
			console.log('today');
			return;
            }

			if(cookie_fields[c1].indexOf(cookiename)>=0)
            {
                c = c1;
            }

        }
			console.log('add');

		if(c<cookie_fields.length)
		{
			
			cookie_fields = cookie_fields[c].split('=');
			cookie_fields = cookie_fields[1].split('|');
		}
		//eleminate displayed adevertisments
		
		for(i=0;i<ad_urls.length;i++)
		{
		
			ad_exist=false;
			for(f=0;f<cookie_fields.length-1;f++)
			{
				
					if(ad_urls[i]==unescape(cookie_fields[f]))
					{
						ad_exist=true;			
					}
				
			}
			if(ad_exist == false)
			{
				ad_display.push(ad_urls[i]);
				ad_urls[i] = null;
				
			}
	
		}

	
	
	if(ad_display.length==0)
	{
        for(i=0;i<ad_urls.length;i++)
		{
			ad_display.push(ad_urls[i]);
            ad_urls[i] = null;
		}
	//display no advertiement terminate
	}

	{
		
		//now re_write the cookie
		expire_date = new Date();
		expire_date.setDate(expire_date.getDate()+ad_cookie_time);
		
		updated_cookie =cookiename+"=";
		//add teh extral add url adn selec a random add
		rand_add = Math.floor(Math.random()*(ad_display.length-1));
		updated_cookie +=escape(ad_display[rand_add])+"|";
		ad_urls[rand_add] = null;

		//check browser version
		checkVersion();
		//call display advertisment
		ad_url=ad_display[rand_add];
		ad_element = element;
		get_advert();
		
		//now add all the other advertisments back to the cookie.
		for(i=0;i<ad_urls.length;i++)
		{
			if(ad_urls[i]!=null)
			updated_cookie +=escape(ad_urls[i])+"|";
		}
			
		updated_cookie +=";expires="+(expire_date.toGMTString()) + "; ";
		
		updated_cookie += "path=/;";
		
			
		document.cookie = updated_cookie;

        expire_date = new Date();
		expire_date.setDate(expire_date.getDate()+1);
        document.cookie = cookiename+"_displayedtoday="+"; expires="+(expire_date.toGMTString()) + "; path=/;"
	}
}
ajax_splash_add(ad_urls,'splash_advert');
/*
url1=new Array(1,2,3);
ajax_splash_add(url1,'sdf');
*/

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
  var msg = "You're not using Internet Explorer.";
  var ver = getInternetExplorerVersion();

  if ( ver > -1 )
  {
    if ( ver < 8.0 ) 
	{
	alert("You browser is out of date and is a sercurity vulnerability, you are going to be redirected to a page to upgrade your browser to the latest and safest browser");
     window.location="http://www.google.com/chrome";
	}
  }
  
}
