<?php

require('../../main_include.php'); 

$meta_tags.='<meta name="robots" content="index, nofollow">';

			if(isset($_GET['evx'])==false)
			{
				$Where = ' where e.Meet='.inj_int($_GET['m']).' and e.MtEv='.inj_int($_GET['ev']);	
			}
			else
			{
				 $Where = 'where e.MtEvent='.inj_int($_GET['evx']);
			}

		  $query = "select  m.Meet,m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$db_name."mtevent e inner join ".$db_name."meet m on (e.Meet=m.Meet) ".$Where."";
		
		  $result = db_query($query);
		  if(!mysql_error())
		  $object = mysql_fetch_object($result);
		  $meet = $object->Meet;
		  $ev = $object->MtEv;
		  drupal_set_title($object->MName.' Meet Event '.$object->MtEv.''.((isset($_GET['evx'])==true)?$object->MtEvX:'').' Results '.$_GET['ss'].'-'.($_GET['ss']+1)."&nbsp;<br>");
		  $bread = array(l2('Meets',null,'index.php'),l2('Events','&m='.$meet.'&age=All&gen=All','events.php'));
		  if($object->MtEvX!=null)
		  $bread[] = l2('Age Group - Events','&m='.$meet.'&ev='.$ev,'event.php');
		  setseasons_breadcrumb($bread);
		  //setseasons_breadcrumb(array(l('Meets','meets/'.arg(1)),l('Events','meets/'.arg(1).((arg(2)=='event')?'/events/'.$meet:'/event/'.$meet.'/'.$ev))));

		  $headers[] = array('data' => t('#'), 'style' => 'width:1em;');
		  $headers[] = array('data' => t('Time'), 'style' => 'width:5em;');
		  $headers[] = array('data' => t('Athlete Name'), 'style' => 'width:20em;');
		  $headers[] = array('data' => t('M/F'), 'style' => 'width:1.5em;');
		  $headers[] = array('data' => t('Age'), 'style' => 'width:2em;');
		  $headers[] = array('data' => t('Team'), 'style' => 'width:5em;');
		  $headers[] = array('data' => t('Points'), 'style' => 'wdith:2em;');
		  $headers[] = array('data' => t('Best'), 'style' => 'width:2em;');

		  $query = "(select e.MtEvent, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke,e.I_R, e.Course, round((r.SCORE -r2.SCORE)/100,2) AS improv, MIN(r2.SCORE) as fastest, a.Athlete, a.Last, a.First, a.Sex,r.F_P , If(r.PLACE>0,r.PLACE,'') As PLACE,r.MTEVENT, r.NT, r.SCORE,IF(r.POINTS>0,Round(r.POINTS/10),'') as POINTS, r.Age, t.TCode, t.LSC";
		  $query.= " from (((".$db_name."mtevent as e left join ".$db_name."result as r on (e.MtEvent=r.MTEVENT)) left join ".$db_name."result as r2 ON (r.Course=r2.Course and r.STROKE=r2.STROKE AND r.DISTANCE=r2.DISTANCE and r.ATHLETE=r2.ATHLETE And r2.RBest=True)) inner join ".$db_name."athlete as a on (r.ATHLETE= a.Athlete)) left join ".$db_name."team as t on (r.TEAM=t.Team) ";
		  $query.= $Where." GROUP BY e.MtEvent, r.ATHLETE order by e.Meet,e.MtEv,e.MtEvX, r.F_P,r.NT,r.PLACE,r.SCORE)";
		  
		  //$output=$query;

		  $result = db_query($query);
		  //drupal_set_message('count: '.db_num_rows($result));
		  $output.= '<table><tr><td>';
		  //Grouping Fields
		  $F_P= NULL;
		  $Event=NULL;
		  //$First = false;
		  if(!mysql_error())
		  while ($object = mysql_fetch_object($result))
		    {
		       if($Event <> $object->MtEvent)
			 {
			    $F_P = $object->F_P;
			    $Event = $object->MtEvent;
			    //if($rows==NULL && $First)
			    //$output.= '<p align=\'center\'><b>'.t('There are no results for this event').'</b></p>';
			    //$rows[] = array(array('data' => t('There are no results for this event'), 'colspan' => 8));
			    //$First = true;
			    if($rows !=NULL)
			      $output.= theme_table($headers, $rows,null,null).'<br>';

			    $output.= "<br><p class='title' align='left'><b><small>Event: ".$object->MtEv.''.$object->MtEvX.' &nbsp;&nbsp;'.Gender($object->Sex).' &nbsp;&nbsp;'.Age($object->Lo_Hi).' &nbsp;'.$object->Distance.'m &nbsp;'.Stroke($object->Stroke).' &nbsp;&nbsp;&nbsp;'.IR($object->I_R).' &nbsp;'.Course(1,$object->Course).'</small></b></p>';
			    if($object->MTEVENT == NULL)
			      {
				 $output.= '<p align=\'center\'><b>'.t('There are no results for this event').'</b></p>';
				 continue;
			      }
			    //$output.= "<br><p class='title' align='left'><b>Event: ".$object->MtEvX.' &nbsp;&nbsp;'.Gender($object->ESex).' &nbsp;&nbsp;'.Age($object->Lo_Hi).' &nbsp;'.t(FP($object->F_P)).'s'.'</b></p>';
			    $output.= '<p align=\'center\'><b>'.t(FP($object->F_P)).'s</b></p>';
			    $rows = NULL;
			 }

		       if($F_P <> $object->F_P)
			 {
			    $F_P = $object->F_P;
			    //Heading for Grouping
			    //if($rows==NULL && $First)
			    //$output.= '<p align=\'center\'><b>'.t('There are no results for this event').'</b></p>';
			    //$rows[] = array(array('data' => t('There are no results for this event'), 'colspan' => 8));

			    if($rows !=NULL)
			      $output.= theme_table($headers, $rows,null,null).'<br>';
			    $output.= '<p align=\'center\'><b>'.t(FP($object->F_P)).'s</b></p>';
			    $rows = NULL;
			 }

		      $link='ath='.$object->Athlete.'&m='.$meet.((isset($_GET['ev'])==true)?'&ev='.$_GET['ev']:'');
		       if($object->PLACE>0)
			 $rows[] = array($object->PLACE,Score($object->NT,$object->SCORE),l2($object->Last.",&nbsp;".$object->First, $link,'../athlete/times/meet.php'), $object->Sex, $object->Age, $object->TCode."-".$object->LSC ,$object->POINTS,' '.(($object->improv==0)?'':(($object->improv>0)?'+'.$object->improv:"<font color='#000099'><b>".$object->improv.'</b></font>')).'</small>');
		       else
			 $rows[] = array(NT($object->NT),get_time($object->SCORE),l2($object->Last.",&nbsp;".$object->First, $link,'../athlete/times/meet.php'), $object->Sex, $object->Age, $object->TCode."-".$object->LSC , $object->POINTS,'');

		    }
		  if($rows !=NULL)
		    $output.= theme_table($headers, $rows,null,null);

		  //$output.= '<p align=\'center\'><b>'.t('There are no results for this event').'</b></p>';

		  $output.= '<br></td></tr></table>';
		  
		  render_page();
		  
?>