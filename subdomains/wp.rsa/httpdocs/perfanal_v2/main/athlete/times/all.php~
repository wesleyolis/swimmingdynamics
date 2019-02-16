<?php
 $output.= athlete_heading($tm4db,$season,arg(3));

	     $query="select r.fina, ".course_conversion($tm4db,$season)." as Converted, r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet,r.Result, (Select Group_CONCAT( CONCAT(CONCAT(SplitIndex,','),Split)) as Splits From ".$tm4db."splits_".$season." Where SplitID=r.Result) as Splits from ".$tm4db."result_".$season." as r left join ".$tm4db."meet_".$season." as m on (r.MEET=m.Meet ) where r.ATHLETE=%d and r.I_R!='R' order by r.COURSE, r.STROKE,r.Distance,r.nt, m.Start desc";

	     $result = db_query($query,arg(3));

	     $header[] = array('width' => '80px');
	     $header[] = array('width' => '50px');
	     $header[] = array('width' => '15px');
	     $header[] = array('width' => '80px');
	     $header[] = array('width' => '25px');
	      $header[] = array('data' => t(''),'width' => '50px');
	     $header[] = array('width' => '90px');
	     $header[] = array('width' => '320px');

	     $output.= "To view splits move cursor above result.  N.B Use Firefox and If splits off page use mouse wheel to srcoll.<br>Please note that LC Times Converted to SC Times and visa vesa.<br>";
	     $output.= "<br>To do a split comparisons, scroll down to the events such as 200 breaststroke,<br> then check (tick the square box's) of the results splits that you would like to compare.<br>";
	     $output.= "Once you selected the results to compare, click the '25m' button next to the event to see the graphs.<br> i.e 200 breaststroke - Split Comparisons at '25m' (button)";
	     $i=0;

	     $Course=null;
	     $Stroke=null;
	     $Distance=null;
	     $splits = false;
	     $temp='';
	     while ($object = db_fetch_object($result))
	       {
		       
		       
	

		
		    
		  If($Course != $object->COURSE)
		    {
		       if($rows !=null)
		       {
			 $temp.= theme('table', $header, $rows,array('onmouseout'=>'hide_dis();'));
			 $temp.='</form>'; 
			 $output.=$temp;
			     $temp='';
			
		       }
		       $rows =null;
		       $Course = $object->COURSE;

		       $output.="<br><p><b>".Course(1,$Course)."</b></p>";
		    }
		  If($Stroke != $object->STROKE || $Distance != $object->DISTANCE)
		    {
		       if($rows !=null)
		       {
			     
			     
			 $temp.= theme('table', $header, $rows,array('onmouseout'=>'hide_dis();'));
			  $temp.='</form>'; 
			 $output.=$temp;
			     $temp='';
			
			      //$output.='</form>';
		       }
		       $rows =null;
		       $Stroke = $object->STROKE;
		       $Distance = $object->DISTANCE;
		       
		       $temp.='<form method="POST" action="?q=athlete/'.arg(1).'/split/'.arg(3).'/'.$Stroke.'/'.$Distance.'" >';
		      
			    //$output.='<form method="POST" action="?q=athlete/'.arg(1).'/split/'.arg(3).'/'.$Stroke.'/'.$Distance.'" >';
			$temp.="<br><b>".$Distance.'m '.Stroke($Stroke).'</b><span id="no_print"><b> - Split Comparisons at <input name="submit" id="no_print" type="submit" value="25m"></b></span>';
		        
		       //." - ".l(t('Split Comparisons'),'athlete/'.arg(1).'/split/'.arg(3).'/'.$Stroke.'/'.$Distance.'/'.$Course.'/'.(($Course=='L')?4:2))
		       $splits = false;
		    }
		  
		    if($object->Splits!=null)
		    {
			    $splits = true;
		    }
		  $time = Score($object->NT,$object->SCORE);
		 // $link = 'meets/'.arg(1).'/event/results/'.$object->MtEvent;
		  $link = 'athlete/'.arg(1).'/meet_times/'.arg(3).'/'.$object->Meet;
		   $link_splits = 'athlete/'.arg(1).'/splits/'.arg(3).'/'.$object->Result;
		  $rows[] = array('onmouseover'=>"dis_splits(".$i.",'".$object->Splits."')" ,'data'=> array($time,  FP($object->F_P)."<div class='cellrel'><div class='cellinfodis' id='s".$i."'></div></div>",$object->I_R,get_time($object->Converted).'<small>'.(($object->COURSE=='L')?'S':(($object->COURSE=='S')?'L':'')).'</small>',$object->fina,($object->Splits==null || $object->NT!=0|| $object->I_R=='R')?null:'<input id="no_print" type="checkbox" name="sp['.$object->Result.']" value="'.$object->Result.'">'.l(t('splits'), $link_splits), get_date($object->Start), l(t($object->MName), $link)));
		  $i++;
	       }

	     $temp.= theme('table', $header, $rows,array('onmouseout'=>'hide_dis();'));
	     $temp.='</form>'; 
	     $output.=$temp;
	      $temp='';

	     $output.='<br><br><br><br><br><br><br>';
?>