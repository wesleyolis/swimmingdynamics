<?php
 $output.= athlete_heading($tm4db,$season,arg(3));

	     $query="select r.fina, ".course_conversion($tm4db,$season)." as Converted, r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet, (Select Group_CONCAT( CONCAT(CONCAT(SplitIndex,','),Split)) as Splits From ".$tm4db.".splits_".$season." Where SplitID=r.Result) as Splits from ".$tm4db.".result_".$season." as r left join ".$tm4db.".meet_".$season." as m on (r.MEET=m.Meet ) where r.ATHLETE=%d order by r.COURSE, r.STROKE,r.Distance,r.nt, m.Start";

	     $result = db_query($query,arg(3));

	     $header[] = array('width' => '80px');
	     $header[] = array('width' => '50px');
	     $header[] = array('width' => '15px');
	     $header[] = array('width' => '80px');
	     $header[] = array('width' => '40px');
	     $header[] = array('width' => '90px');
	     $header[] = array('width' => '320px');

	     $output.= "To view splits move cursor above result.  N.B Use Firefox and If splits off page use mouse wheel to srcoll.<br>Please note that LC Times Converted to SC Times and visa vesa.<br>";

	     $i=0;

	     $Course=null;
	     $Stroke=null;
	     $Distance=null;
	     while ($object = db_fetch_object($result))
	       {
		  If($Course != $object->COURSE)
		    {
		       if($rows !=null)
			 $output.= theme('table', $header, $rows,array('onmouseout'=>'hide_dis();'));
		       $rows =null;
		       $Course = $object->COURSE;

		       $output.="<br><p><b>".Course(1,$Course)."</b></p>";
		    }
		  If($Stroke != $object->STROKE || $Distance != $object->DISTANCE)
		    {
		       if($rows !=null)
			 $output.= theme('table', $header, $rows,array('onmouseout'=>'hide_dis();'));
		       $rows =null;
		       $Stroke = $object->STROKE;
		       $Distance = $object->DISTANCE;

		       $output.="<br><b>".$Distance.'m '.Stroke($Stroke)."</b>";
		    }

		  $time = Score($object->NT,$object->SCORE);
		  $link = 'meets/'.arg(1).'/event/results/'.$object->MtEvent;
		  $rows[] = array('onmouseover'=>"dis_splits(".$i.",'".$object->Splits."')" ,'data'=> array($time,  FP($object->F_P)."<div class='cellrel'><div class='cellinfodis' id='s".$i."'></div></div>",$object->I_R,get_time($object->Converted).'<small>'.(($object->COURSE=='L')?'S':(($object->COURSE=='S')?'L':'')).'</small>',$object->fina, get_date($object->Start), l(t($object->MName), $link)));
		  $i++;
	       }

	     $output.= theme('table', $header, $rows,array('onmouseout'=>'hide_dis();'));

	     $output.='<br><br><br><br><br><br><br>';
?>