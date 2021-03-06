<?php
$option = 'my_records';
require('../../../main_include.php');
require('../heading.php');

drupal_set_title('Records of '.$heading);

 $query = "Select SQL_CACHE a.*  from ".$db_name."athlete as a where athlete=".inj_int($_GET['ath']).' limit 1';

 $results = db_query($query);
 
 	if(!mysql_error())
	if($object = mysql_fetch_object($results))
	{
		$name = $object->First;	
		$last = $object->Last;
		$pref = $object->Pref;
	}
	if(($name!='' & $last!='')|| ($perf!='' & $last!=''))
	{
		
		$Sd = $config['ranking_dd'];
		$Sm = $config['ranking_mm'];
		
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
		$results = db_query("SELECT if(DATEDIFF('".$seas.'-'.$Sm.'-'.$Sd."',r.RecDate)<=0,1,0)  as highlight, n.*, r.*  from ".$db_name."recname as n inner join ".$db_name."records as r on (n.record = r.Record) Where (instr(LCase(r.RecText),LCase('".$name."')) !=0 and instr(LCase(r.RecText),LCase('".$last."'))!=0) or (instr(LCase(r.RecText),LCase('".$pref."')) !=0 and instr(LCase(r.RecText),LCase('".$last."'))!=0)Order by n.recfile,n.record,r.course,r.sex,r.RecDate desc");
		//Grouping
		$Rec=null;
		$Gender=null;
		$Course=null;
		if(!mysql_error())
		while($object = mysql_fetch_object($results))
		{
			if($Rec <> $object->Record)
			{
				$Rec = $object->Record;
				if($rows !=NULL)
				$output.= theme_table($headers, $rows,null,null).'<br>';
				$output.= '<p class=\'highlight\' align=\'center\'><b>'.$object->RecFile.', '.$object->Descript.' - '.$object->Year.'</b></p>';
				$rows = NULL;
			}
			if($Gender <> $object->Sex ||$Course <> $object->Course )
			{
				$Gender = $object->Sex;
				$Course = $object->Course;
				if($rows !=NULL)
				$output.= theme_table($headers, $rows,null,null).'<br>';
				$output.= '<div align=\'center\'><b>'.t(Course(1,$object->Course).' - '.Gender($object->Sex)).'</b></div>';
				$rows = NULL;
			}
			$rows[] = array('class'=>($object->highlight==1?'highlight':''), 'data'=>array(get_time($object->RecTime),LO_HI($object->Lo_Age,$object->Hi_Age),$object->Distance,Stroke($object->Stroke),$object->I_R,$object->RecText,get_date($object->RecDate),$object->RecLSC.'-'.$object->RecTeam,$object->Ex,$object->Division));
		}
		$output.=theme_table($headers, $rows,null,null);
	}
	render_page();

?>