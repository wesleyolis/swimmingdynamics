<?php
	header('Content-type: text/html');
	function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}

	$render_start = microtime_float();
	//ob_start();
	
	//$js_script="<script type=\"text/javascript\">\n  var _gaq = _gaq || [];\n  _gaq.push(['_setAccount', 'UA-8330106-3']);\n  _gaq.push(['_setDomainName', '.co.za']);\n  _gaq.push(['_trackPageview']);\n  (function() {\n    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;\n    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';\n    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);\n  })();\n</script>\n";

	$js_include='';
	$output='';
	$rows=false;
	require('main_config.php');
	require('site_matcher.php');
	require('main_functions.php');
	require('main_durpal_functions.php');
	require('cache_control.php');
	require('google_adverts.php');
	require('meta_tags.php');
	require('render_page.php');
	
	$arg=(isset($_GET['q']) == true)?explode($exp,$_GET['q']):'';
?>