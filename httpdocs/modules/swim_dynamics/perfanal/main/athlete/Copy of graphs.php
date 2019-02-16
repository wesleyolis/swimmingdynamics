<?php
 $output.= athlete_heading($tm4db,$season,arg(3));

	     if(arg(4) ==null||arg(5) ==null)
	       {
		  $header[] = array('data' => t('Distance'), 'width' => '70px');
		  //$header[] = array('data' => t('Stroke'), 'width' => '80px');

		  $result = db_query("select r.DISTANCE, r.STROKE from ".$tm4db."result_".$season." r where r.ATHLETE=%d and r.I_R <> 'R' and r.NT =0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE",arg(3));
		  $output.='Please select an option.<br><br>';
		  $rows[] = array(l('ALL'.stroke($object->STROKE),'athlete/'.arg(1).'/graphs/'.arg(3).'/ALL/ALL'));
		  while ($object = db_fetch_object($result))
		    {
		       $rows[] = array(l($object->DISTANCE.' '.stroke($object->STROKE),'athlete/'.arg(1).'/graphs/'.arg(3).'/'.$object->DISTANCE.'/'.$object->STROKE));
		    }
		  $output.= theme_table(null, $rows,array('id'=>'hyper','class'=>'hyper'),null).'<br>';
	       }
	     else
	       {

		  $stroke = array(1 => "Freestyle", 2 => "Back", 3 => "Breast", 4 => "Butterfly", 5 => "Medley");

		  $header[] = array('data' => t('Time'), 'width' => '70px');
		  $header[] = array('data' => t('Distance'), 'width' => '70px');
		  $header[] = array('data' => t('Stroke'), 'width' => '80px');
		  $header[] = array('data' => t('Rounds'), 'width' => '50px');
		  $header[] = array('data' => t('Date'), 'width' => '90px');
		  $header[] = array('data' => t('Meet'), 'width' => '300px');

		  $key_lookup = array();
		  if(arg(4)=='ALL' & arg(5)=='ALL')
		    $result = db_query("select r.COURSE, r.SCORE, r.DISTANCE, r.STROKE, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." r, ".$tm4db."meet_".$season." m where r.ATHLETE=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE, r.SCORE",arg(3));
		  else
		    $result = db_query("select r.COURSE, r.SCORE, r.DISTANCE, r.STROKE, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." r, ".$tm4db."meet_".$season." m where r.ATHLETE=%d and r.distance= %d and r.stroke=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE, r.SCORE",arg(3),arg(4),arg(5));
		  while ($object = db_fetch_object($result))
		    {
		       $key_lookup[] = array($object->COURSE, $object->STROKE, $object->DISTANCE);
		    }

		  for ($i=0; $i<count($key_lookup); $i++)
		    {
		       $query = "select  r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." as r left join ".$tm4db."meet_".$season." as m on (r.MEET=m.Meet ) where r.SCORE !=0 AND r.NT=0 AND r.STROKE=".$key_lookup[$i][1]." and r.DISTANCE=".$key_lookup[$i][2]." and  r.ATHLETE=".arg(3)." order by r.STROKE,r.Distance, m.Start,r.COURSE,r.F_P desc,r.Score";

		       $result = db_query($query);
		       //			drupal_set_message($query);

		       $last_stroke = NULL;
		       $last_distance = NULL;
		       $rowsL = array();
		       $rowsS = array();
		       $graph_hdrs = array();
		       $graph_dataL = array();
		       $graph_dataS = array();

		       while ($object = db_fetch_object($result))
			 {
			    //				print_r($object);

			    $time = Score(0,$object->SCORE);
			    $f_p = (strtolower($object->F_P) == 'f') ? 'Final' : 'Prelim';

			    $start_date = explode("-", $object->Start);
			    $date = Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			    $graph_hdrs[$date.$object->F_P] = $date.'|';
			    $link = 'meets/'.arg(1).'/event/results/'.$object->MtEvent;

			    if (strtolower($object->COURSE) == "l")
			      {
				 $rowsL[] = array($time, $object->DISTANCE.'m', $stroke[$object->STROKE], $f_p, $date, l(t($object->MName), $link));
				 $graph_dataL[$date.$object->F_P]=($object->SCORE).'|';

			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null)
				     $graph_dataL[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 $rowsS[] = array($time, $object->DISTANCE.'m', $stroke[$object->STROKE], $f_p, $date, l(t($object->MName), $link));
				 $graph_dataS[$date.$object->F_P]=($object->SCORE).'|';
			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataL[$date.$object->F_P] != null)
				     $graph_dataS[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null)
				     $graph_dataL[$date.$object->F_P]='-|';
			      }

			    $last_stroke = $object->STROKE;
			    $last_distance = $object->DISTANCE;
			 }

		       $splitterL = array();
		       $splitterS = array();

		       $splitterL[] = array(array('data' => '<div style="font-weight: bold; color: blue;">Long Course</div>', 'colspan' => 6));
		       $splitterS[] = array(array('data' => '<div style="font-weight: bold; color: orange;">Short Course</div>', 'colspan' => 6));

		       $rows = array_merge($splitterL, $rowsL);
		       $rowsL = array_merge($rows, $splitterS);
		       $rows = array_merge($rowsL, $rowsS);

		       $graph_hdrs_data = NULL;
		       foreach($graph_hdrs as $key => $value)
			 {
			    $graph_hdrs_data.= $value;
			 }
		       $graph_dataL_data = NULL;
		       foreach($graph_dataL as $key => $value)
			 {
			    $graph_dataL_data.= $value;
			 }
		       $graph_dataS_data = NULL;
		       foreach($graph_dataS as $key => $value)
			 {
			    $graph_dataS_data.= $value;
			 }
	
		       $graph_data = '?headers='.$graph_hdrs_data;
		       $graph_data.= (($graph_dataL_data) ? '&data='.$graph_dataL_data : '');
		       $graph_data.= (($graph_dataS_data) ? '&data2='.$graph_dataS_data : '');

		       $gdataS[] = array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><div class='cellrel'><div class='graphsback'>".theme_image(path()."/images/swimming.jpg",null,null,array('width'=>'645px','height'=>'150px'),false)."</div>".theme_image(path()."/grapher.php".$graph_data,null,null,null,false)."</div></td></tr></table>", 'colspan' => 6,'align'=>'center');

		       $rows[] = $gdataS;

		       $output.= '<br><div style="font-weight: bold;">'.$last_distance.'m '.$stroke[$last_stroke].'</div><hr>';
		       $output.= theme('table', $header, $rows).'<br>';
		       $gdataS = NULL;
		       $rows   = NULL;
		       $rowsL  = NULL;
		       $rowsS  = NULL;
		    }
	       }
?>