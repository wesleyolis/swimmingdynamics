/**
* 
**/

function addtofavorites(url, description)
{
   if (window.sidebar) // Mozilla Firefox
   {
   	window.sidebar.addPanel(description, url,"");
   }
   else if( document.all ) //IE
   {
   	window.external.AddFavorite(url, description);
   }
   else
   {
   	return true;
   }
}
