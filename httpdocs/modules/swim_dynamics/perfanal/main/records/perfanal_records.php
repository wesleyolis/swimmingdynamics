<?php
$season = get_seasons();
$tm4db = variable_get('perfanal_database', 'perfanal');
switch(arg(2))
{
	case'age':{
		$Sd = variable_get('perfanal_ranking_dd', '01');
		$Sm = variable_get('perfanal_ranking_mm', '01');
		$results = db_query("Select SQL_CACHE * From ".$tm4db."recname_".$season." Where Record=%d",arg(3));
		$object = db_fetch_object($results);
		drupal_set_title($object->RecFile.' - '.$object->Descript.' Records &nbsp;&nbsp;'.Age(arg(4)));
		setseasons_breadcrumb(array(l('Records','records/'.arg(1)),l('Age Groups','records/'.arg(1).'/ages/'.arg(3))));
		$headers[] = array('data' => t('Time'), 'width' => '70px');
		$headers[] = array('data' => t('Dis'), 'width' => '30px');
		$headers[] = array('data' => t('Stroke'), 'width' => '70px');
		$headers[] = array('data' => t('I/R'), 'width' => '20px');
		$headers[] = array('data' => t('Holder'), 'width' => '400px');
		$headers[] = array('data' => t('Date'), 'width' => '80px');
		$headers[] = array('data' => t('Team'), 'width' => '70px');
		$headers[] = array('data' => t('Ex'), 'width' => '20px');
		$headers[] = array('data' => t('Div'), 'width' => '20px');
		
		$results = db_query("SELECT *, if(DATEDIFF('".$season.'-'.$Sm.'-'.$Sd."',RecDate)<=0,1,0)  as highlight from ".$tm4db."records_".$season." Where record=%d and Lo_Age =%d and Hi_Age =%d  Order by Course,Sex,I_R,Stroke,Distance",arg(3),floor((arg(4)/100)),(arg(4)%100));
		//Grouping
		$Gender=null;
		$Course=null;
		while($object = db_fetch_object($results))
		{
			if($Gender <> $object->Sex ||$Course <> $object->Course )
			{
				$Gender=$object->Sex;
				$Course=$object->Course;
				if($rows !=NULL)
				$output.= theme('table', $headers, $rows).'<br>';
				$output.= '<p align=\'center\'><b>'.t(Course(1,$object->Course).' - '.Gender($object->Sex)).'</b></p>';
				$rows = NULL;
			}
			$rows[] = array('class'=>($object->highlight==1?'highlight':''), 'data'=>array(get_time($object->RecTime),$object->Distance,Stroke($object->Stroke),$object->I_R,$object->RecText,get_date($object->RecDate),$object->RecLSC.'-'.$object->RecTeam,$object->Ex,$object->Division));
		}
		$output.=theme('table', $headers, $rows);
	}
	break;
	
	case'ages':
	{
		$results = db_query("Select SQL_CACHE * From ".$tm4db."recname_".$season." Where Record=%d",arg(3));
		$object = db_fetch_object($results);
		drupal_set_title($object->RecFile." Records");
		setseasons_breadcrumb(array(l('Records','records/'.arg(1))));
		$headers[] = array('data' => t('Age Group'), 'width' => '120px');
		
		$results = db_query("Select SQL_CACHE  distinct ((Lo_Age*100)+Hi_Age) as Age From ".$tm4db."records_".$season." Where Record=%d ORDER by Lo_Age,Hi_Age",arg(3));
		if(db_num_rows($results)==1)
		{
			$object = db_fetch_object($results);
			drupal_goto('records/'.arg(1).'/age/'.arg(3).'/'.$object->Age);
		}
		while($object = db_fetch_object($results))
		$rows[] = array(l(Age($object->Age),'records/'.arg(1).'/age/'.arg(3).'/'.$object->Age));
		$output='<br/>'.l('Lastest Records Breakers','records/'.arg(1).'/latest/'.arg(3));
		$output.=theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
	}
	break;
	case'latest':
	{
		$breakers_days = variable_get('perfanal_display_breakers', '30');
		if(arg(3)!=null)
		{
		$results = db_query("Select SQL_CACHE * From ".$tm4db."recname_".$season." Where Record=%d",arg(3));
		$object = db_fetch_object($results);
		}
		drupal_set_title((arg(3)==null?'':$object->RecFile.', '.$object->Descript.' - '.$object->Year).' Latest Records Breakers');
		$bread[] =l('Records','records/'.arg(1));
		if(arg(3)!=null)
		$bread[] = l('Age Groups','records/'.arg(1).'/ages/'.arg(3));
		setseasons_breadcrumb($bread);
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
		$results = db_query("SELECT n.*, r.*  from ".$tm4db."recname_".$season." as n inner join ".$tm4db."records_".$season." as r on (n.record = r.Record) Where DATEDIFF(CURDATE(),r.RecDate)<=".$breakers_days." ".(arg(3)?' and n.record='.arg(3):''). " Order by n.recfile,n.record,r.course,r.sex,r.RecDate desc");
		//Grouping
		$Rec=null;
		$Gender=null;
		$Course=null;
		while($object = db_fetch_object($results))
		{
			if($Rec <> $object->Record & arg(3)==null )
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
			$rows[] = array(get_time($object->RecTime),LO_HI($object->Lo_Age,$object->Hi_Age),$object->Distance,Stroke($object->Stroke),$object->I_R,$object->RecText,get_date($object->RecDate),$object->RecLSC.'-'.$object->RecTeam,$object->Ex,$object->Division);
		}
		$output.=theme('table', $headers, $rows);
	}
	break;
	
	default:
	{
		drupal_set_title("Records");
		setseasons_breadcrumb(null);
		$headers[] = array('data' => t('Name'), 'width' => '70px');
		$headers[] = array('data' => t('Description'), 'width' => '110px');
		$headers[] = array('data' => t('Course'), 'width' => '60px');
		$headers[] = array('data' => t('Year'), 'width' => '30px');
		$headers[] = array('data' => t('Flag'), 'width' => '30px');
		$results = db_query("Select * From ".$tm4db."recname_".$season." Order BY Year desc, Course");
		while($object = db_fetch_object($results))
		$rows[] = array(l($object->RecFile,'records/'.arg(1).'/ages/'.$object->Record),$object->Descript,Course(1,$object->Course),$object->Year,$object->Flag);
		$output="<div style='float: left'><br/>".l('Lastest Records Breakers','records/'.arg(1).'/latest/');
		$output.=theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null).'</div>';
		$output.="<div  style='text-align:center;float: right'><b>Congratulations to the following<br> Record Breakers</b>".block_prefanal_record_breakers().'</div>';
	}
	break;
}
?>