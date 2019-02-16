<?php
require('../../main_include.php'); 

$meta_tags.='<meta name="robots" content="index, nofollow">';

	if($_GET['evx']==null)
	{
		$Where = ' where e.Meet='.inj_int($_GET['m']).' and e.MtEv='.inj_int($_GET['ev']);	
	}
	else
	{
		 $Where = 'where e.MtEvent='.inj_int($_GET['evx']);
	}



	     $query = "select m.meet,m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$db_name."mtevent e right join ".$db_name."meet m on (e.Meet=m.Meet) ".$Where." ";

	     $result = db_query($query);
	     $object = mysql_fetch_object($result);
	     $meet = $object->meet;
	     $ev = $object->MtEv;
	     drupal_set_title($object->MName.' Meet Event '.$object->MtEv.''.(($_GET['evx']!=null)?$object->MtEvX:'').' Relay Results'.' '.$_GET['ss'].'-'.($_GET['ss']+1)."&nbsp;<br>");
	    // setseasons_breadcrumb(array(l('Meets','meets/'.arg(1)),l('Events','meets/'.arg(1).((arg(2)=='event')?'/events/'.$meet:'/event/'.$meet.'/'.$ev))));
	    	  
	    	  $bread = array(l2('Meets',null,'meets.php'),l2('Events','&m='.$_GET['m'].'&age=All&gen=All','events.php'));
		  if($_GET['evx']!=null)
		  $bread[] = l2('Age Group - Events','&m='.$_GET['m'].'&ev='.$_GET['ev'],'event.php');
		  setseasons_breadcrumb($bread);
	    
	     $joins ='';
	     $brackets='';
	     $athletes='';
	     for($c=1;$c<8;$c++)
	       {
		  $joins.=' left join '.$db_name.'athlete as a'.$c.' on (y.ATH'.$c.'=a'.$c.'.Athlete))';
		  $brackets.='(';
		  $athletes.=', a'.$c.'.athlete as athlete_'.$c.', a'.$c.'.Last as last_'.$c.', a'.$c.'.First as first_'.$c.'';
	       }
	     $query ="(select e.MtEvent, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke,e.I_R, e.Course,r.MTEVENT, r.F_P, If(r.PLACE>0,r.PLACE,'') As PLACE, r.NT, r.SCORE, IF(r.POINTS>0,Round(r.POINTS/10),'') as POINTS";
	     $query .=", y.Sex, y.LETTER, t.TName, t.TCode, t.LSC ".$athletes;
	     $query .=" From ".$brackets."(((".$db_name."mtevent as e left join ".$db_name."result as r on (e.MtEvent=r.MTEVENT)) left join ".$db_name."relay as y on (r.ATHLETE=y.Relay)) left JOIN ".$db_name."team as t on (r.TEAM=t.Team)) ".$joins;
	     $query .=" ".$Where." and (r.I_R='R' Or r.I_R IS NULL) Order By e.Meet,e.MtEv,e.MtEvX, r.F_P,r.NT,r.PLACE,r.SCORE)";
	    // $output.=$query;
	
	     $result = db_query($query);

	     $headers[] = array('data' => t('#'), 'style' => 'width:em;');
	     $headers[] = array('data' => t('Time'), 'style' => 'width:3.5em;');
	     $headers[] = array('data' => t('Relay Team'), 'style' => '250px');
	     $headers[] = array('data' => t('M/F'), 'style' => 'width:3.5em;');
	     $headers[] = array('data' => t('Team'), 'style' => 'width:4em;');
	     $headers[] = array('data' => t('Points'), 'style' => 'width:3em;');

	     $athheaders[] = array('data' => t('Athlete'), 'style' => 'width:50em');

	     $output.= '<table><tr><td>';
	     //Grouping Fields
	     $F_P= NULL;
	     $Event = NULL;
	     $First = false;
	     if(!mysql_error())
	     while ($object = mysql_fetch_object($result))
	       {
		  if($Event <> $object->MtEvent)
		    {
		       $F_P = $object->F_P;
		       $Event = $object->MtEvent;
							
		       if($rows !=NULL)
			 $output.= theme_table($headers, $rows,null,null).'<br>';

		       $output.= "<br><br><p class='title' align='left'><b><small>Event: ".$object->MtEv.''.$object->MtEvX.' &nbsp;&nbsp;'.Gender($object->Sex).' &nbsp;&nbsp;'.Age($object->Lo_Hi).' &nbsp;'.$object->Distance.'m &nbsp;'.Stroke($object->Stroke).' &nbsp;&nbsp;&nbsp;'.IR($object->I_R).' &nbsp;'.Course(1,$object->Course).'</small></b></p><br>';
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
		       //	if($rows==NULL && $First)
		       //	$rows[] = array(array('data' => t('There are no results for this event'), 'colspan' => 8));

		       if($rows !=NULL)
			 $output.= theme_table($headers, $rows,null,null).'<br>';
		       $output.= '<p align=\'center\'><b>'.t(FP($object->F_P)).'s</b></p>';
		       $rows = NULL;
		    }

		 // $link = "swimmers_results/".arg(2)."/toptimes/".$object->Athlete;
		  if($object->PLACE>0)
		    $rows[] = array($object->PLACE,Score($object->NT,$object->SCORE),$object->LETTER.(($object->TName==NULL)?' Team not found':' &nbsp;'.$object->TName), $object->Sex, $object->TCode."-".$object->LSC ,$object->POINTS);
		  else
		    $rows[] = array(NT($object->NT),get_time($object->SCORE),$object->LETTER.(($object->TName==NULL)?' Team not found':' &nbsp;'.$object->TName), $object->Sex, $object->TCode."-".$object->LSC ,$object->POINTS);
		
	       }
	     if($rows !=NULL)
	       $output.= theme_table($headers, $rows,null,null);
		$output.= '</td></tr></table>';
	     
	     render_page();

?>