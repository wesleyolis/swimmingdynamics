<?php

	require('ranking_config.php');
	require('rankings_config_sanc.php');

	drupal_set_title($pref_head.$type[$_GET['type']].' Rankings - Course'."<br/><small>".$heading.'</small>');
	
	$breadcumb[] = l2('Ranking Catagories','','index.php');
	if($config['lsc']=='')
	{
		$breadcumb[] = l2('LSC Filter',substr($curr_param,0,strrpos($curr_param,'&lsc')),'rankings_lsc.php');
	}
	if(($config['rank_time_meet_filter'] == '' && $_GET['type'] != 'fina') || ($config['rank_fina_meet_filter'] == '' && $_GET['type'] == 'fina'))
	{
		$breadcumb[] = l2('Meet Sanction',substr($curr_param,0,strrpos($curr_param,'&sanc')),'rankings_geo.php');
	}
	setseasons_breadcrumb($breadcumb);
	
	
	$output="<br>Please Select a course.<br><br>";
	if($_GET['type']!='time')
	$output.=l2('All Courses',$curr_param.'&c=ALL','rankings_age.php').'<br><br>';
	$output.=l2('Short Course 25m',$curr_param.'&c=S','rankings_age.php').'<br><br>';
	$output.=l2('Long Course 50m',$curr_param.'&c=L','rankings_age.php').'<br><br>';
	if($_GET['type']!='fina')
	$output.=l2('Open Water',$curr_param.'&c=L&op','rankings_age.php').'<br><br>';
	
	
	render_page();

?>
