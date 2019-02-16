<?php
		require('../../main_include.php');
		$display_link=0;
		
		 switch($_GET['gen'])
		    {
		     case 'F': $Gen= "Female";
		       break;
		     case 'M': $Gen="Male";
		       break;
		     case 'X': $Gen="Mixed";
		       break;
		    }
		
		  $query = "select SQL_CACHE m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$db_name."mtevent as e inner join ".$db_name."meet as m on (e.Meet=m.Meet) WHERE e.Meet=".inj_int($_GET['m']);
		
		  $result = db_query($query);
		  if(!mysql_error())
		  {
		  
			  
			  
		  $object = mysql_fetch_object($result);
		  drupal_set_title($object->MName.' Meet '.$Gen.' Team Points Results Rankings'.' '.$_GET['ss'].'-'.($_GET['ss']+1).'');
		  setseasons_breadcrumb(array(l2('Meets','m='.$_GET['m'],'index.php'),l2('Events','&m='.$_GET['m'].'&age=All&gen=All','events.php')));
		 
		  $menu_option = 'team';
		  
		require('meets_menu.php');
		  
		  $output.= "<div style='padding:0.5em 0em 0em 0em;' class='title' align=\'center\'><b>".$Gen." Team Rankings</b></div>";

		  $Where='';
		  switch($_GET['gen'])
		    {
		     case 'F': $Where.= " and a.Sex='F'";
		       break;
		     case 'M': $Where.= " and a.Sex='M'";
		       break;
		    }

				/*Display team rankings*/

		  $headers[] = array('data' => t('#'), 'style' => 'width:2em;');
		  $headers[] = array('data' => t('Score'), 'style' => 'width:2.5em;');
		  $headers[] = array('data' => t('Team Name'), 'style' => 'width:20em;');
		  $headers[] = array('data' => t('Code'), 'style' => 'width:7em;');
		  $headers[] = array('data' => t('Athletes'), 'style' => 'width:4em;');

		  $query="SELECT SUM(r.POINTS)/10 as Sum_Points,t.TName,t.TCode, t.LSC , Count(DISTINCT If(r.I_R='I',r.Athlete,0)) AS Sum_Athletes";
		  $query.=" FROM (".$db_name."result as r  left join ".$db_name."athlete as a on (r.ATHLETE=a.Athlete)) left join ".$db_name."team as t on (r.TEAM=t.Team)";
		  $query.=" Where r.Meet=".inj_int($_GET['m'])." ".$Where." Group by r.Team HAVING SUM(r.POINTS)>0 Order by Sum_Points Desc";

		  $result = db_query($query);

		  $pos = 0;
		  $pos2 = -1;
		  $point=NULL;
		  while ($object = mysql_fetch_object($result))
		    {
		       if($point != $object->Sum_Points)
			 {
			    $point = $object->Sum_Points;
			    $pos++;
			 }
		       $rows[] = array((($pos ==$pos2)?'-':$pos),Round($object->Sum_Points,1),( ($object->TName==NULL)?'Team not found':$object->TName), $object->TCode."-".$object->LSC,$object->Sum_Athletes);
		       if($pos != $pos2)
			 $pos2 = $pos;
		    }
		  if (!$rows)
		    {
		       $rows[] = array(array('data' => t('There are no results for this Gender'), 'colspan' => 5));
		    }
		  
		    $output.= theme_table($headers, $rows,null,null);
		  }
		  
		  render_page();
?>