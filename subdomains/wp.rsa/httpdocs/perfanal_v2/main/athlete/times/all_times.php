<?php
	$option = 'all_times.php';
	require('../../../main_include.php');
	require('../heading.php');


	drupal_set_title('All Time Results - '.$heading);

	     $query="select r.fina, ".course_conversion(0)." as Converted,round(r.POINTS/10,1) as points_r, r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet,r.Result, (Select Group_CONCAT( CONCAT(CONCAT(SplitIndex,','),Split)) as Splits From ".$db_name."splits Where SplitID=r.Result) as Splits from ".$db_name."result as r left join ".$db_name."meet as m on (r.MEET=m.Meet ) where r.ATHLETE=".inj_int($_GET['ath'])." and r.I_R!='R' order by r.COURSE, r.STROKE,r.Distance,r.nt, m.Start desc";

	     $result = db_query($query);
	     
	     
	     $header[] = array('data'=>'Time','style'=>'width:4.5em');
	     $header[] = array('data' => t('Round'),'style'=>'width:2.5em');
	     $header[] = array('data'=>'I/R','style'=>'width:0.8em');
	     $header[] = array('data' => t('Conv LC'),'style'=>'width:5em');
	     $header[] = array('align'=>'right','data'=>'Fina - '.$running_config['fina_points_rank_year'],'colspan'=>2);
         $header[] = array('data' => t('Points'),'style'=>'width:2.5em');
	     $header[] = array('style'=>'width:6em');
	     $header[] = array('style'=>'width:24em');


	     $output.= "To view splits move the cursor over result.  N.B If splits off page use mouse wheel to scroll.<br>Please note that LC Times Converted to SC Times and vice versa. Yard times are converted to short Course Times<br>";
	     $output.= "<br>To do a split comparisons, scroll down to the events such as 200 breaststroke,<br> then tick the square box of the results splits that you would like to compare.<br>";
	     $output.= "Once you have selected the results to compare, click the '25m' button next to the event to see the graphs.<br> i.e 200 breaststroke - Split Comparisons at '25m' (button). Note if there are no splits at 25m, the comparison will be at 50m anyway.";
	     $i=0;

	     $Course=null;
	     $Stroke=null;
	     $Distance=null;
	     $splits = false;
	     $temp='';
	     if(!mysql_error())
	     while ($object = mysql_fetch_object($result))
	       {
		    
		  If($Course != $object->COURSE)
		    {
		       if($rows !=null)
		       {
			  if($splits == true)
			   $temp.=$temp_compare; 
			 $temp.= theme_table($header, $rows,array('onmouseout'=>'hide_dis();'),null);
			 $temp.='</form>'; 
			 $output.=$temp;
			     $temp='';
			
		       }
		       $rows =null;
		       $Course = $object->COURSE;

		       $output.="<br><p><b>".Course(1,$Course)."</b></p>";
		       
		       if($object->COURSE=='Y')
		       $header[3] = array('data' => t('Conv SC'),'style'=>'width:5em');
		       if($object->COURSE=='S')
		       $header[3] = array('data' => t('Conv LC'),'style'=>'width:5em');
		       if($object->COURSE=='L')
		       $header[3] = array('data' => t('Conv SC'),'style'=>'width:5em');
		    }
		  If($Stroke != $object->STROKE || $Distance != $object->DISTANCE)
		    {
		       if($rows !=null)
		       {
			   if($splits == true)
			   $temp.=$temp_compare; 
			     
			 $temp.= theme_table($header, $rows,array('onmouseout'=>'hide_dis();'),null);
			  $temp.='</form>'; 
			 $output.=$temp;
			     $temp='';
			
			      //$output.='</form>';
		       }
		       $rows =null;
		       $Stroke = $object->STROKE;
		       $Distance = $object->DISTANCE;
		       
		       $temp.='<form method="GET" action="split_comp.php" >';
		      $temp.='<input type="hidden" name="db" value="'.$config['db_ident'].'">';
		       $temp.='<input type="hidden" name="ss" value="'.$config['seas_curr'].'">';
		      $temp.='<input type="hidden" name="ath" value="'.$_GET['ath'].'">';
		      $temp.='<input type="hidden" name="st" value="'.$Stroke.'">';
		      $temp.='<input type="hidden" name="dis" value="'.$Distance.'">';
		      $temp.='<input type="hidden" name="cr" value="'.$Course.'">';
		       
			$temp.="<b>".$Distance.'m '.Stroke($Stroke).'</b>';
		        $temp_compare = '<span id="no_print"><b> - Split Comparisons at <input name="submit" id="no_print" type="submit" value="'.($object->COURSE=='L'?'50m':'25m').'"> Click the button</b></span>';
		       $splits = false;
		    }
		  
		    if($object->Splits!=null)
		    {
			    $splits = true;
		    }
		  $time = Score($object->NT,$object->SCORE);

		   $link = 'evx='.$object->MtEvent;
		  $rows[] = array('onmouseover'=>"dis_splits(".$i.",'".$object->Splits."')" ,'data'=> array($time,  FP($object->F_P)."<div class='cellrel'><div class='cellinfodis' id='s".$i."'></div></div>",$object->I_R,get_time($object->Converted).'<small>'.(($object->COURSE=='L')?'S':(($object->COURSE=='S')?'L':'S')).'</small>',$object->fina,($object->Splits==null || $object->NT!=0|| $object->I_R=='R')?null:'<input id="no_print" type="checkbox" name="sp['.$object->Result.']" value="">'.l2(t('splits'), 'ath='.$_GET['ath'].'&sp='.$object->Result,'splits.php'),$object->points_r, get_date($object->Start), l2($object->MName, $link,'../../meets/indi_results.php')));
		  $i++;
	       }
	       if($splits == true)
			   $temp.=$temp_compare; 
			   
	     $temp.= theme_table($header, $rows,array('onmouseout'=>'hide_dis();'),null);
	     $temp.='</form>'; 
	     $output.=$temp;
	      $temp='';

	     $output.='<br><br><br><br><br><br><br>';
	     
	     render_page();
?>