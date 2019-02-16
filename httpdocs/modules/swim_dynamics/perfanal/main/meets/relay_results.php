<?php
 switch( arg(2))
	       {
		case 'event':
		  $Where = 'where e.MtEvent=%d';
		  break;
		case 'events':
		  $Where = 'where e.Meet=%d and e.MtEv=%d';
		  break;
		case '':
	       }

	     $query = "select m.meet,m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$tm4db."mtevent_".$season." e right join ".$tm4db."meet_".$season." m on (e.Meet=m.Meet) ".$Where." ";
	     $result = db_query($query,arg(4),arg(5));
	     $object = db_fetch_object($result);
	     $meet = $object->meet;
	     $ev = $object->MtEv;
	     drupal_set_title($object->MName.' Meet Results'.' '.arg(1).'-'.(arg(1)+1)."&nbsp;<br>");
	     setseasons_breadcrumb(array(l('Meets','meets/'.arg(1)),l('Events','meets/'.arg(1).((arg(2)=='event')?'/events/'.$meet:'/event/'.$meet.'/'.$ev))));

	     $joins ='';
	     $brackets='';
	     $athletes='';
	     for($c=1;$c<8;$c++)
	       {
		  $joins.=' left join '.$tm4db.'athlete_'.$season.' as a'.$c.' on (y.ATH'.$c.'=a'.$c.'.Athlete))';
		  $brackets.='(';
		  $athletes.=', a'.$c.'.athlete as athlete_'.$c.', a'.$c.'.Last as last_'.$c.', a'.$c.'.First as first_'.$c.'';
	       }
	     $query ="(select e.MtEvent, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke,e.I_R, e.Course,r.MTEVENT, r.F_P, If(r.PLACE>0,r.PLACE,'') As PLACE, r.NT, r.SCORE, IF(r.POINTS>0,Round(r.POINTS/10),'') as POINTS";
	     $query .=", y.Sex, y.LETTER, t.TName, t.TCode, t.LSC ".$athletes;
	     $query .=" From ".$brackets."(((".$tm4db."mtevent_".$season." as e left join ".$tm4db."result_".$season." as r on (e.MtEvent=r.MTEVENT)) left join ".$tm4db."relay_".$season." as y on (r.ATHLETE=y.Relay)) left JOIN ".$tm4db."team_".$season." as t on (r.TEAM=t.Team)) ".$joins;
	     $query .=" ".$Where." and (r.I_R='R' Or r.I_R IS NULL) Order By e.Meet,e.MtEv,e.MtEvX, r.F_P,r.NT,r.PLACE,r.SCORE)";
	    // $output.=$query;

	     $result = db_query($query,arg(4),arg(5));

	     $headers[] = array('data' => t('#'), 'width' => '20px');
	     $headers[] = array('data' => t('Time'), 'width' => '60px');
	     $headers[] = array('data' => t('Relay Team'), 'width' => '250px');
	     $headers[] = array('data' => t('M/F'), 'width' => '30px');
	     $headers[] = array('data' => t('Team'), 'width' => '80px');
	     $headers[] = array('data' => t('Points'), 'width' => '30px');

	     $athheaders[] = array('data' => t('Athlete'), 'width' => '250px');

	     $output.= '<table><tr><td>';
	     //Grouping Fields
	     $F_P= NULL;
	     $Event=NULL;
	     $First = false;
	     while ($object = db_fetch_object($result))
	       {
		  if($Event <> $object->MtEvent)
		    {
		       $F_P = $object->F_P;
		       $Event = $object->MtEvent;
							/*if($rows==NULL && $First)
							$rows[] = array(array('data' => t('There are no results for this event'), 'colspan' => 8));
							$First = true;*/

		       if($rows !=NULL)
			 $output.= theme('table', $headers, $rows).'<br>';

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
			 $output.= theme('table', $headers, $rows).'<br>';
		       $output.= '<p align=\'center\'><b>'.t(FP($object->F_P)).'s</b></p>';
		       $rows = NULL;
		    }

		  $link = "swimmers_results/".arg(2)."/toptimes/".$object->Athlete;
		  if($object->PLACE>0)
		    $rows[] = array($object->PLACE,Score($object->NT,$object->SCORE),$object->LETTER.(($object->TName==NULL)?' Team not found':' &nbsp;'.$object->TName), $object->Sex, $object->TCode."-".$object->LSC ,$object->POINTS);
		  else
		    $rows[] = array(NT($object->NT),get_time($object->SCORE),$object->LETTER.(($object->TName==NULL)?' Team not found':' &nbsp;'.$object->TName), $object->Sex, $object->TCode."-".$object->LSC ,$object->POINTS);
						
						/*//$object = db_fetch_array($result);
						$aths='';
						for ($i=0; $i<8; $i++)
						if($object['athlete_'.$i]!=NULL)
							$aths .= l($object['last_'.$i].",".$object['first_'.$i], "swimmers_results/".arg(2)."/toptimes/".$object['athlete_'.$i]).' &nbsp;&nbsp;';

						$rows[] = array('',array('data' =>$aths , 'colspan' => 7));
						$aths = NULL;
*/
	       }
	     if($rows !=NULL)
	       $output.= theme('table', $headers, $rows);
					/*if (!$rows)+
					{
						$rows[] = array(array('data' => t('There are no results for this event, click '.l(t('here'), 'meets/'.arg(1).'/'.arg(2).'/event/'.arg(4)).' to go back.'), 'colspan' => 8));
					}
					$output.= theme('table', $headers, $rows);*/
	     $output.= '</td></tr></table>';

?>