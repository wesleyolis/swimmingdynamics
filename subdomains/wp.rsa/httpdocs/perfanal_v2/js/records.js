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


