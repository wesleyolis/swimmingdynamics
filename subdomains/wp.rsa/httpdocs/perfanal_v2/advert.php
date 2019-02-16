<?php
$url = $_SERVER['REQUEST_URI'];

		     if(false && (strpos($url,'main/meets/events.php')>0 || strpos($url,'main/meets/meets_info.php')>0 || strpos($url,'rankings_time.php')>0 || strpos($url,'rankings_team_points.php')>0 || strpos($url,'rankings_fina.php')>0 || strpos($url,'rankings_indi_points.php')>0 || strpos($url,'main/athlete/times/')>0))
			{
				header("Cache-Control: max-age=360, must-revalidate");


$page_title_inline1="<div style='position: relative'><div id='splash_advert' style='display:none;position: absolute; left:-10px; top:-10px; z-index: 1;'><div height='15px' id='splash_advert_butons' style='text-decoration:underline;color:#FFFFFF;position: absolute; right:10px; top:20px; z-index: 2;'></div><div id='splash_advert_content'></div></div></div>";
$page_title_inline1.="<script type='text/javascript' defer='defer' src='".$app_relative."js/ajax_advertisment.js'></script>";
$page_title_inline1.= "<script type='text/javascript'><!--\n";
$page_title_inline1.= "ad_urls = new Array('http://wp.rsa.swimdynamics.co.za/advert/tg_splash/index.php','http://wp.rsa.swimdynamics.co.za/advert/excab_splash/index.php');ad_cookie_time=7;ad_time=2;";
$page_title_inline1.= "\n//--></script>";
$page_title_inline=$page_title_inline1.$page_title_inline;
			}
			

 ?>
