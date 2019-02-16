<?php

	require('ranking_config.php');
	require('rankings_config_sanc.php');
	require('rankings_age_group_where.php');
	require('rankings_title.php');
	
	$breadcumb[] = l2('Stroke & Dis',substr($curr_param,0,strrpos($curr_param,'&str')),'rankings_time_str_dis.php');

	setseasons_breadcrumb($breadcumb);
	
	drupal_set_title($pref_head.' '.$type[$_GET['type']].' Rankings'." as of ".$_GET['d'].'<br><small>'.$heading.' '.Gender($_GET['gen']).' '.Age(($_GET['lo']*100)+$_GET['hi']).' '.$_GET['dis'].' '.stroke($_GET['str']).' '.Course(1,$_GET['c']).'</small> '.(($rankings_predicative==false)?'':"<small><br>Athletes ages on ".$_GET['d'].'</small>'));
	
	$Where.= ' and r.Stroke='.inj_int($_GET['str']);
	$Where.= ' and r.Distance='.inj_int($_GET['dis']).' ';
		  
   $headers[] = array('data' => t('#'), 'width' => 'width:1em;');
   $headers[] = array('data' => t('Time'), 'width' => 'width:3.5em;');
   $headers[] = array('data' => t('Athlete Name'), 'width' => 'width:20em;');
   $headers[] = array('data' => t('M/F'), 'width' => 'width:1.5em;');
   $headers[] = array('data' => t('Age'), 'width' => 'width:1.5em;');
   $headers[] = array('data' => t('Team'), 'width' => 'width:5.5em;');
   $headers[] = array('data' => t('Meet'));

   $query="Select SQL_CACHE r.MTEVENT,m.MName,a.Athlete, a.Last, a.First, a.Sex, ".$ath_age.", r.score, t.TCode, t.LSC, r.Age as sage";
   $query.=" FROM (((".$db_name."result as r left join ".$db_name."athlete as a on (r.ATHLETE=a.Athlete)) ".$filter_team." ) left join ".$db_name."meet as m on (r.Meet=m.Meet))";
   $query.=" WHERE r.nt=0 and ".$Where." ".$Where_rank[0]." order by r.Score limit ".$limit_results_query;
 
   $result = db_query($query);

   $pos=1;
   $pre_pos=0;
   
   if(!mysql_error())
   while ($object = mysql_fetch_object($result))
     {
	$link='ath='.$object->Athlete;
	$rows[] = array(($pre_pos==$object->score)?'-':$pos,get_time($object->score),l2($object->Last.",&nbsp;".$object->First, $link,'../athlete/times/top_times.php'), $object->Sex, $object->age, $object->TCode."-".$object->LSC,l2($object->MName,'evx='.$object->MTEVENT,'../meets/indi_results.php'));
	$pos++;
     }
   if (!$rows)
     $rows[] = array(array('data' => 'There are no athletes results found matching your criteria.', 'colspan' => 7));

   $output.= theme_table($headers, $rows,null,null);

  render_page();

?>