<?php
$meta_tags = '';
function meta_tags()
{
	global $page_title,$config,$meta_tags,$page_title_inline,$seas_reg;
	
	$pos = strrpos($page_title,' ',(-strlen($page_title)+64));
	$subheading = substr($page_title_inline,($pos - strlen($seas_reg) - 1));
	$patten = Array('/<small>/','/<\/small>/','/<br>/','/<br\/>/','/&nbsp;/');
	$replace = Array('','',' ',' ',' ');
	$subheading = trim(preg_replace($patten,$replace,$subheading));
	$meta_tags.='<meta name="description" content="'.$subheading .'. Offers to date Records and Rankings(Times,Fina,Points,Team) Individual athlete and Meets/Gala Results '.$config['searchengine_desc'].'" />';
	$meta_tags.='<meta name="keywords" content="Swimming,Dynamics,Swimming Dyanmics,Rankings,Records,Times,Fina,Results,Athelte,Swimmer,Best,swimdynamics,swimdynamicks,province,aquatics,'.$config['searchengine_keywords'].','.$config['title'].','.$config['desc'].'" />';
}
?>
