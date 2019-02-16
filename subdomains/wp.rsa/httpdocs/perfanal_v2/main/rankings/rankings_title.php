<?php

$output.="<div class='no_print' style='padding:5px;' align='right'>".(($_GET['type']=='fina')?l2('Base Times - '.($running_config['fina_points_rank_year']),'q=base_times/'.$running_config['fina_points_rank_year'].'/'.$_GET['c'],'../fina/index.php').' | ':'').'Included '.l2('Meets','d='.$_GET['d'].'&sanc='.$_GET['sanc'].'&course='.$_GET['c'],'rankings_meets.php')." & ".l2('Teams','&lsc='.(($cntry != $_GET['lsc'] & $_GET['lsc'] != 'All')?$_GET['lsc']:'').'&include=true','rankings_teams.php')." | ".l2('Detailed Information','d='.$_GET['d'],'rankings_info.php')."</div>";

	
	
	$breadcumb[]  = l2('Ranking Catagories','','index.php');
	if($config['lsc']=='')
	{
		$breadcumb[] = l2('LSC Filter',substr($curr_param,0,strrpos($curr_param,'&lsc')),'rankings_lsc.php');
	}
	if(($config['rank_time_meet_filter'] == '' && $_GET['type'] != 'fina') || ($config['rank_fina_meet_filter'] == '' && $_GET['type'] == 'fina'))
	{
		$breadcumb[] = l2('Meet Sanction',substr($curr_param,0,strrpos($curr_param,'&sanc')),'rankings_geo.php');
	}
	$breadcumb[] = l2('Course',substr($curr_param,0,strrpos($curr_param,'&c')),'rankings_course.php');
	$breadcumb[] = l2('Age & Gender',substr($curr_param,0,strrpos($curr_param,'&gen')),'rankings_age.php');
	
	drupal_set_title($pref_head.' '.$type[$_GET['type']].' Rankings as of '.$_GET['d'].'<br><small>'.$heading.' '.Gender($_GET['gen']).' '.Age(($_GET['lo']*100)+$_GET['hi']).' '.Course(1,$_GET['c']).'</small> '.(($rankings_predicative==false)?'':"<small><br>Athletes ages on ".$_GET['d'].'</small>'));

?>