<?php
/*
tics.sportza.co.za/
tics.sportza.co.za/
?q=ranking
ranking
*/
function current_page($url)
{
   
   $ex_url = explode('/',$url);
   
   return str_replace('?q=','',$ex_url[(($ex_url[3]=='perfanal')?4:3)]);


}

function  season($url)
{
	$ex_url = explode('/',$url);
	return $ex_url[(($ex_url[3]=='perfanal')?5:4)];
	
}

echo current_page("http://wpaquatics.sportza.co.za/?q=ranking/2007/time/WP/ALL/L/male/99").'<br>';
echo current_page("http://wpaquatics.sportza.co.za/ranking/2007/time/WP/ALL/L/male/99").'<br>';
echo season("http://wpaquatics.sportza.co.za/?q=ranking/2007/time/WP/ALL/L/male/99").'<br>';
echo season("http://wpaquatics.sportza.co.za/ranking/2007/time/WP/ALL/L/male/99").'<br>';

echo current_page("http://192.168.1.4/perfanal/?q=ranking/2007/time/WP/ALL/L/male/99").'<br>';
echo current_page("http://192.168.1.4/perfanal/ranking/2007/time/WP/ALL/L/male/99").'<br>';
echo season("http://192.168.1.4/perfanal/q=ranking/2007/time/WP/ALL/L/male/99").'<br>';
echo season("http://192.168.1.4/perfanal/ranking/2007/time/WP/ALL/L/male/99").'<br>';



echo ((current_page("http://wpaquatics.sportza.co.za/?q=")!=null)?'true':'false').'<br>';
echo current_page("http://wpaquatics.sportza.co.za").'<br>';
echo season("http://wpaquatics.sportza.co.za/").'<br>';
echo season("http://wpaquatics.sportza.co.za/").'<br>';

echo $_SERVER['REQUEST_URI'];

?>