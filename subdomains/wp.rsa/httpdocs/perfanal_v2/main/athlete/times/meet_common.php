<?php


	     $result= db_query("Select SQL_CACHE m.MName from ".$db_name."meet as m Where m".$where);
	     if(!mysql_error())
	     {
		     $object = mysql_fetch_object($result);
	     
		     $meet_name = $object->MName;
		     $output.= "To view splits move cursor above result. N.B If splits off page use mouse wheel to srcoll down.<br><br><b>".$object->MName." Meet</b><br><br>";
	     }
	     $query="select ".course_conversion(0)." as Converted, e.MtEv,e.MtEvX, r.MtEvent, r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE, r.F_P,r.I_R,r.Course,r.Result, (Select Group_CONCAT( CONCAT(CONCAT(SplitIndex,','),Split)) as Splits From ".$db_name."splits Where SplitID=r.Result) as Splits from ".$db_name."result as r left join ".$db_name."mtevent as e on (r.MTEVENT=e.MtEvent ) where r.ATHLETE=".inj_int($_GET['ath'])." and r.I_R!='R' and r".$where." order by e.MtEv,e.MtEvX";

	     $result = db_query($query);

	     $header[] = array('data' => t('Event'), 'style'=>'width:3em');
	     $header[] = array('data' => t('Time'), 'style'=>'width:4.5em');
	     $header[] = array('data' => t('Distance'), 'style'=>'width:4.5em');
	     $header[] = array('data' => t('Stroke'), 'style'=>'width:5em');
	     $header[] = array('data' => t('Conv'), 'style'=>'width:4.5em');
	     $header[] = array('data' => t(''));
	     $header[] = array('data' => t('Round'), 'style'=>'width:4em');
	     $header[] = array('data' => t('I/R'), 'style'=>'width:2em');
	     $header[] = array('data' => t('Course'), 'style'=>'width:4em');

	     $i=0;
	     if(!mysql_error())
	     while ($object = mysql_fetch_object($result))
	       {
		  $time = Score($object->NT,$object->SCORE);
		  //$link = 'meets/'.arg(1).'/event/results/'.$object->MTEVENT;
		  $link = ((isset($_GET['ev'])==true)?'m='.$_GET['m'].'&ev='.$_GET['ev']:'evx='.$object->MtEvent);
		  $rowsL[] =array('onmouseover'=>"dis_splits(".$i.",'".$object->Splits."')" ,'data'=> array(l2($object->MtEv.' '.$object->MtEvX,$link,'../../meets/indi_results.php'),$time,$object->DISTANCE.'m'."<div class='cellrel'><div class='cellinfodis' id='s".$i."'></div></div>", Stroke($object->STROKE),get_time($object->Converted),($object->Splits==null)?null:l2(t('splits'), 'ath='.$_GET['ath'].'&sp='.$object->Result,'splits.php'), FP($object->F_P),IR($object->I_R), $object->Course));
		  $i++;
	       }

	      ?>