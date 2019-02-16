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
