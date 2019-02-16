<?php
$output.= athlete_heading($tm4db,$season,arg(3));

 if(arg(2)=='meet_times')
 {
	$where =  ".Meet=".arg(4);	 
 }
 else
 {
	 $where = ".meet=(select m.meet from ".$tm4db."meet_".$season." as m inner join ".$tm4db."result_".$season." as r on (r.meet=m.meet) where r.ATHLETE=".arg(3)."  order by m.Start Desc limit 1)";
 }

	     $result= db_query("Select SQL_CACHE m.MName from ".$tm4db."meet_".$season." as m Where m".$where);
	     $object = db_fetch_object($result);
	     $output.= "To view splits move cursor above result. Best viewed with FireFox  N.B If splits off page use mouse wheel to srcoll down.<br><br><b>".$object->MName." Meet</b><br><br>";

	     $query="select ".course_conversion($tm4db,$season)." as Converted, e.MtEv,e.MtEvX,r.MTEVENT, r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE, r.F_P,r.I_R,r.Course,r.Result, (Select Group_CONCAT( CONCAT(CONCAT(SplitIndex,','),Split)) as Splits From ".$tm4db."splits_".$season." Where SplitID=r.Result) as Splits from ".$tm4db."result_".$season." as r left join ".$tm4db."mtevent_".$season." as e on (r.MTEVENT=e.MtEvent ) where r.ATHLETE=%d and r.I_R!='R' and r".$where." order by e.MtEv,e.MtEvX";

	     $result = db_query($query,arg(3));

	     $header[] = array('data' => t('Event'), 'width' => '50px');
	     $header[] = array('data' => t('Time'), 'width' => '70px');
	     $header[] = array('data' => t('Distance'), 'width' => '70px');
	     $header[] = array('data' => t('Stroke'), 'width' => '80px');
	     $header[] = array('data' => t('Conv SC'), 'width' => '70px');
	     $header[] = array('data' => t(''));
	     $header[] = array('data' => t('Rounds'), 'width' => '50px');
	     $header[] = array('data' => t('I/R'), 'width' => '50px');
	     $header[] = array('data' => t('Course'), 'width' => '40px');

	     $i=0;
	     while ($object = db_fetch_object($result))
	       {
		  $time = Score($object->NT,$object->SCORE);
		  $link = 'meets/'.arg(1).'/event/results/'.$object->MTEVENT;
		  $link_splits = 'athlete/'.arg(1).'/splits/'.arg(3).'/'.$object->Result;
		  $rowsL[] =array('onmouseover'=>"dis_splits(".$i.",'".$object->Splits."')" ,'data'=> array(l($object->MtEv.' '.$object->MtEvX,$link),$time,$object->DISTANCE.'m'."<div class='cellrel'><div class='cellinfodis' id='s".$i."'></div></div>", Stroke($object->STROKE),get_time($object->Converted),($object->Splits==null)?null:l(t('splits'), $link_splits), FP($object->F_P),IR($object->I_R), $object->Course));
		  $i++;
	       }

	     $output.= theme('table',$header, $rowsL,array('id'=>'hyper','class'=>'hyper'),null);

	     $output.='<br><br><br><br><br><br><br>';
	     ?>