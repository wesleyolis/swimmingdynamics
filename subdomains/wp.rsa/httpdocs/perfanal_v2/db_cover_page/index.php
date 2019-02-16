<?php
	require('../main_include.php');
	if(file_exists ($conf['url'].'/'.$_GET['db'].'.php'))
	{
		$output=file_get_contents($conf['url'].'/'.$_GET['db'].'.php', TRUE);
	}
	else
	{
		$output='<br>'.$conf['desc'].'<br><br>Custom db page not available.';
	}
	
	render_page();
?>

