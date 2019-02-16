<?php
	require('ranking_config.php');
	require('rankings_config_sanc.php');
	require('rankings_age_group_where.php');
	require('rankings_title.php');
	
	setseasons_breadcrumb($breadcumb);

   $headers[] = array('data' => t('#'), 'style' => 'width:2em;');
   $headers[] = array('data' => t('Score'), 'style' => 'width:3.5em;');
   $headers[] = array('data' => t('Team Name'), 'style' => 'width:20em;');
   $headers[] = array('data' => t('Code'), 'style' => 'width:6em;');
   $headers[] = array('data' => t('Athletes'), 'style' => 'width:4em;');

   $query="SELECT SQL_CACHE Round(SUM(r.POINTS)/10,1) as Sum_Points,t.TName,t.TCode, t.LSC , Count(DISTINCT If(r.I_R='I',r.Athlete,0)) AS Sum_Athletes";
   $query.=" FROM (".$db_name."result as r left join ".$db_name."athlete as a on (r.ATHLETE=a.Athlete)) left join ".$db_name."team as t on (r.TEAM=t.Team)";

   $query.=" Where r.points != 0 and ".$Where." ".$Where_rank[0]." Group by r.Team HAVING SUM(r.POINTS)>0 Order by Sum_Points Desc limit ".$limit_results_query;
   
  

   $result = db_query($query);

   $pos = 0;
   $pos2 = -1;
   $point=NULL;
   if(!mysql_error())
   while ($object = mysql_fetch_object($result))
     {
	if($point != $object->Sum_Points)
	  {
	     $point = $object->Sum_Points;
	     $pos++;
	  }
	$rows[] = array((($pos ==$pos2)?'-':$pos),$object->Sum_Points,( ($object->TName==NULL)?'Team not found':$object->TName), $object->TCode."-".$object->LSC,$object->Sum_Athletes);
	if($pos != $pos2)
	  $pos2 = $pos;
     }
   if (!$rows)
     {
	$rows[] = array(array('data' => 'There are no athletes results found matching your criteria.', 'colspan' => 5));
     }
     $output='Age filtering done on athlete\'s age in result.';
   $output.=  theme_table($headers, $rows,null,null);
   render_page();

?>