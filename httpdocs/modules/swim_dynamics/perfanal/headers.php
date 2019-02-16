<?php
$url = $_SERVER['REQUEST_URI'];
if(strpos($url,'ranking')>0 || strpos($url,'athlete')>0 || strpos($url,'standards')>0 || strpos($url,'records')>0||strpos($url,'fina')>0)
{
	header("Cache-Control: max-age=10800, public"); 
}
?>
