<?php

	require('ranking_config.php');
	require('rankings_config_sanc.php');
	require('rankings_age_group_where.php');
	require('rankings_title.php');
	
	setseasons_breadcrumb($breadcumb);

 $headers[] = array('data' => t('#'), 'style' => 'width:1em;');
   $headers[] = array('data' => t('Score'), 'style' => 'width:3.5em;');
   $headers[] = array('data' => t('Athlete Name'), 'style' => 'width:20em;');
   $headers[] = array('data' => t('M/F'), 'style' => 'width:2.5em;');
   $headers[] = array('data' => t('Age'), 'style' => 'width:2em;');
   $headers[] = array('data' => t('Team'), 'style' => 'width:5.5em;');

   $query = "select SQL_CACHE a.Athlete, a.Last, a.First, a.Sex, ".$ath_age.", round((sum(r.POINTS)/10),1) as Sum_Points, t.TCode, t.LSC from ";
   $query.=" (".$db_name."result as r inner join ".$db_name."athlete as a on (r.ATHLETE= a.Athlete)) ".$filter_team."";
   $query.=" Where r.points!=0 and".$Where." ".$Where_rank[0]." and r.I_R='I' GROUP BY  r.ATHLETE having Sum(r.POINTS)>0 order by Sum_Points DESC,a.last limit ".$limit_results_query;
   $result = db_query($query);
  // echo $query;
   
   $pos=1;
   $pre_pos=0;
   if(!mysql_error())
   while ($object = mysql_fetch_object($result))
     {
	$link='ath='.$object->Athlete;
	$rows[] = array(($pre_pos==$object->Sum_Points)?'-':$pos,($pre_pos=$object->Sum_Points),l2($object->Last.",&nbsp;".$object->First, $link,'../athlete/times/top_times.php'), $object->Sex, $object->age, $object->TCode."-".$object->LSC);
	$pos++;
     }
   if (!$rows)
     {
	$rows[] = array(array('data' => 'There are no athletes results found matching your criteria!', 'colspan' => 6));
     }
   $output.= theme_table($headers, $rows,null,null);
   
   	render_page();
   
   ?>