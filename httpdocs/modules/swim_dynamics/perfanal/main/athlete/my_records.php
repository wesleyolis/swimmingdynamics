<?php
$output.= athlete_heading($tm4db,$season,arg(3));
/*
$season = get_seasons();
$tm4db = variable_get('perfanal_database', 'perfanal');
 */

 $query = "Select SQL_CACHE a.*  from ".$tm4db."athlete_".$season." as a where Athlete= %d";
 $results= db_query($query,arg(3));
	if($object = db_fetch_object($results))
	{
	$name = $object->First;	
	$last = $object->Last;
	$pref = $object->Pref;
	}
	
	{
		$Sd = variable_get('perfanal_ranking_dd', '01');
		$Sm = variable_get('perfanal_ranking_mm', '01');
		
		$headers[] = array('data' => t('Time'), 'width' => '70px');
		$headers[] = array('data' => t('Age'));
		$headers[] = array('data' => t('Dis'), 'width' => '30px');
		$headers[] = array('data' => t('Stroke'), 'width' => '70px');
		$headers[] = array('data' => t('I/R'), 'width' => '20px');
		$headers[] = array('data' => t('Holder'), 'width' => '350px');
		$headers[] = array('data' => t('Date'), 'width' => '80px');
		$headers[] = array('data' => t('Team'), 'width' => '70px');
		$headers[] = array('data' => t('Ex'), 'width' => '20px');
		$headers[] = array('data' => t('Div'), 'width' => '20px');
		$results = db_query("SELECT if(DATEDIFF('".$season.'-'.$Sm.'-'.$Sd."',r.RecDate)<=0,1,0)  as highlight, n.*, r.*  from ".$tm4db."recname_".$season." as n inner join ".$tm4db."records_".$season." as r on (n.record = r.Record) Where (instr(LCase(r.RecText),LCase('".$name."')) !=0 and instr(LCase(r.RecText),LCase('".$last."'))!=0) or (instr(LCase(r.RecText),LCase('".$pref."')) !=0 and instr(LCase(r.RecText),LCase('".$last."'))!=0)Order by n.recfile,n.record,r.course,r.sex,r.RecDate desc");
		//Grouping
		$Rec=null;
		$Gender=null;
		$Course=null;
		while($object = db_fetch_object($results))
		{
			if($Rec <> $object->Record)
			{
				$Rec = $object->Record;
				if($rows !=NULL)
				$output.= theme('table', $headers, $rows).'<br>';
				$output.= '<p class=\'highlight\' align=\'center\'><b>'.$object->RecFile.', '.$object->Descript.' - '.$object->Year.'</b></p>';
				$rows = NULL;
			}
			if($Gender <> $object->Sex ||$Course <> $object->Course )
			{
				$Gender = $object->Sex;
				$Course = $object->Course;
				if($rows !=NULL)
				$output.= theme('table', $headers, $rows).'<br>';
				$output.= '<div align=\'center\'><b>'.t(Course(1,$object->Course).' - '.Gender($object->Sex)).'</b></div>';
				$rows = NULL;
			}
			$rows[] = array('class'=>($object->highlight==1?'highlight':''), 'data'=>array(get_time($object->RecTime),LO_HI($object->Lo_Age,$object->Hi_Age),$object->Distance,Stroke($object->Stroke),$object->I_R,$object->RecText,get_date($object->RecDate),$object->RecLSC.'-'.$object->RecTeam,$object->Ex,$object->Division));
		}
		$output.=theme('table', $headers, $rows);
	}

?>