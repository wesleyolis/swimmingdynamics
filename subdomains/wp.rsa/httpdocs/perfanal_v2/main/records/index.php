<?php

require('../../main_include.php');

$meta_tags.='<meta name="robots" content="index, follow">';

if(isset($arg[0])==false)
$arg[0]=null;

switch($arg[0])
{
	case'age':
	case'season':
	case'seasons':
	{
		if(isset($arg[1])==true)
		{
		$results = db_query('Select SQL_CACHE * From '.$db_name.'recname Where Record='.inj_int($arg[1]));
		$object = mysql_fetch_object($results);
		if(mysql_error())
		 exit();	
		
		 drupal_set_title($object->RecFile.' - '.$object->Descript.(($arg[0]=='season'|| $arg[0]=='seasons')?($arg[0]=='seasons'?'Over All for the Season':' '.ucfirst($arg[0])).' Records Breakers ':' Records'.(isset($arg[2])==true?(strtoupper($arg[2])!='ALL'?(' >&nbsp;'.Age($arg[2])):''):'')));
		}
		else
		{
			drupal_set_title((($arg[0]=='season'|| $arg[0]=='seasons')?($arg[0]=='seasons'?'Over All for the Season':' '.ucfirst($arg[0])).' Records Breakers ':' Records'.(isset($arg[2])==true?(strtoupper($arg[2])!='ALL'?(' >&nbsp;'.Age($arg[2])):''):'')));
		}
		 
		 
		 if($arg[0]=='seasons')
		$arg[0]='season';
		
		$crumb=null;
		$crumb[] = l('Records','');
		if(isset($arg[2])==true)
		if($arg[2]!='All')
		if($arg[0] !='season')
		$crumb[] = l('Age Groups','ages/'.$arg[1]);
		setseasons_breadcrumb($crumb);
		$arg[2] = strtoupper($arg[2]);
		$headers[] = array('data' => t('Time'), 'width' => '70px');
		$headers[] = array('data' => t('Dis'), 'width' => '30px');
		$headers[] = array('data' => t('Stroke'), 'width' => '70px');
		$headers[] = array('data' => t('I/R'), 'width' => '20px');
		$headers[] = array('data' => t('Holder'), 'width' => '400px');
		$headers[] = array('data' => t('Date'), 'width' => '80px');
		$headers[] = array('data' => t('Team'), 'width' => '70px');
		$headers[] = array('data' => t('Ex'), 'width' => '20px');
		$headers[] = array('data' => t('Div'), 'width' => '20px');
		
		$query = "SELECT r.*,rn.*, ".($arg[0]=='season'?'0':("if(DATEDIFF('".($_GET['ss']+1).'-'.$config['ranking_mm'].'-'.$config['ranking_dd']."',r.RecDate)<=365,1,0)"))."  as highlight from ".$db_name.'records as r inner join '.$db_name.'recname as rn on (r.record = rn.record) Where 1=1 '.(($arg[0]=='season' && $arg[1] ==null)?'':' and r.record='.inj_int($arg[1])).($arg[0]=='season'?(" and DATEDIFF('".($_GET['ss']+1).'-'.$config['ranking_mm'].'-'.$config['ranking_dd']."',r.RecDate)<=365 "):(($arg[2]=='ALL')?'':' and r.Lo_Age='.floor($arg[2]/100).' and r.Hi_Age='.($arg[2]%100))).' Order by r.record,r.Lo_Age,r.Hi_age,r.Course,r.Sex,r.I_R,r.Stroke,r.Distance';
		//echo $query;
		$results = db_query($query);
		/*
		//Grouping
		$Gender=null;
		$Course=null;
		if(!mysql_error())
		while($object = mysql_fetch_object($results))
		{
			if($Gender <> $object->Sex ||$Course <> $object->Course )
			{
				$Gender=$object->Sex;
				$Course=$object->Course;
				if($rows !=NULL)
				$output.= theme_table($headers, $rows,null,null).'<br>';
				$output.= '<p align=\'center\'><b>'.t((($arg[2]=='ALL'||$arg[0]=='season')?LO_HI($object->Lo_Age,$object->Hi_Age).', ':'').Course(1,$object->Course).' - '.Gender($object->Sex)).'</b></p>';
				$rows = NULL;
			}
			$rows[] = array('class'=>($object->highlight==1?'highlight':''), 'data'=>array(get_time($object->RecTime),$object->Distance,Stroke($object->Stroke),$object->I_R,$object->RecText,get_date($object->RecDate),$object->RecLSC.'-'.$object->RecTeam,$object->Ex,$object->Division));
		}
		
		$output.=theme_table($headers, $rows,null,null);
		
		*/
		
		$Rec=null;
		$Gender=null;
		$Course=null;
		
		if(!mysql_error())
		while($object = mysql_fetch_object($results))
		{
			if($Rec <> $object->Record && $arg[2]==null )
			{
				$Rec = $object->Record;
				if($rows !=NULL)
				$output.= theme_table($headers, $rows,null,null).'<br>';
				$output.= '<p class=\'highlight\' align=\'center\'><b>'.$object->RecFile.', '.$object->Descript.' - '.$object->Year.'</b></p>';
				$rows = NULL;
			}
			if(($Gender <> $object->Sex ||$Course <> $object->Course ) && isset($arg[3])==false)
			{
				$Gender = $object->Sex;
				$Course = $object->Course;
				if($rows !=NULL)
				$output.= theme_table( $headers, $rows,null,null).'<br>';
				$output.= '<p align=\'center\'><b>'.t((($arg[2]=='ALL'||$arg[0]=='season')?LO_HI($object->Lo_Age,$object->Hi_Age).', ':'').Course(1,$object->Course).' - '.Gender($object->Sex)).'</b></p>';
				$rows = NULL;
			}
			$rows[] = array('class'=>($object->highlight==1?'highlight':''), 'data'=>array(get_time($object->RecTime),$object->Distance,Stroke($object->Stroke),$object->I_R,$object->RecText,get_date($object->RecDate),$object->RecLSC.'-'.$object->RecTeam,$object->Ex,$object->Division));
		}
		$output.=theme_table($headers, $rows,null,null);
		
		
		
		
		
	}
	break;
	
	case'ages':
	{
		$results = db_query('Select SQL_CACHE * From '.$db_name.'recname Where Record='.inj_int($arg[1]));
		if(!mysql_error())
		{
			$object = mysql_fetch_object($results);
			drupal_set_title($object->RecFile." Records");
			setseasons_breadcrumb(array(l('Records',$arg[1])));
			$headers[] = array('data' => t('Age Group'), 'width' => '120px');
			
			$results = db_query('Select SQL_CACHE  distinct ((Lo_Age*100)+Hi_Age) as Age From '.$db_name.'records Where Record='.inj_int($arg[1]).' ORDER by Lo_Age,Hi_Age');
			if(!mysql_error())
			{
				if(mysql_num_rows($results) == 1)
				{
					$object = mysql_fetch_object($results);
					drupal_goto($page_file,'q=age/'.$arg[1].'/All');
				}
				$rows[] = array(l('All Ages','age/'.$arg[1].'/ALL'));
				while($object = mysql_fetch_object($results))
				$rows[] = array(l(Age($object->Age),'age/'.$arg[1].'/'.$object->Age));
				$output = '<br/>'.l('Seasons records breakers','season/'.$arg[1]);
				if($_GET['ss']==$seas)
				$output.= '<br/>'.l('Latest record breakers','latest/'.$arg[1]);
			}
			$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
		}
	}
	break;
	case'latest':
	
	{
		$breakers_days = $config['display_breakers'];
		if($arg[1]!=null)
		{
			$results = db_query('Select SQL_CACHE * From '.$db_name.'recname Where Record='.inj_int($arg[1]));
			if(!mysql_error())
			$object = mysql_fetch_object($results);
		}
		drupal_set_title(($arg[1]==null?'':$object->RecFile.', '.$object->Descript.' - '.$object->Year).' Latest Records Breakers ');
		$output.='<br/>';
		$bread[] =l('Records','');
		if($arg[1]!=null)
		$bread[] = l('Age Groups','ages/'.$arg[1]);
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
		$results = db_query('SELECT n.*, r.*  from '.$db_name.'recname as n inner join '.$db_name.'records as r on (n.record = r.Record) Where DATEDIFF(CURDATE(),r.RecDate)<='.$breakers_days.' '.($arg[1]!=null?' and n.record='.inj_int($arg[1]):''). ' Order by n.recfile,n.record,r.course,r.sex,r.RecDate desc');
		//Grouping
		$Rec=null;
		$Gender=null;
		$Course=null;
		if(!mysql_error())
		while($object = mysql_fetch_object($results))
		{
			if($Rec <> $object->Record & $arg[1]==null )
			{
				$Rec = $object->Record;
				if($rows !=NULL)
				$output.= theme_table($headers, $rows).'<br>';
				$output.= '<p class=\'highlight\' align=\'center\'><b>'.$object->RecFile.', '.$object->Descript.' - '.$object->Year.'</b></p>';
				$rows = NULL;
			}
			if($Gender <> $object->Sex ||$Course <> $object->Course )
			{
				$Gender = $object->Sex;
				$Course = $object->Course;
				if($rows !=NULL)
				$output.= theme_table( $headers, $rows).'<br>';
				$output.= '<div align=\'center\'><b>'.t(Course(1,$object->Course).' - '.Gender($object->Sex)).'</b></div>';
				$rows = NULL;
			}
			$rows[] = array(get_time($object->RecTime),LO_HI($object->Lo_Age,$object->Hi_Age),$object->Distance,Stroke($object->Stroke),$object->I_R,$object->RecText,get_date($object->RecDate),$object->RecLSC.'-'.$object->RecTeam,$object->Ex,$object->Division);
		}
		$output.=theme_table($headers, $rows);
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

		$results = db_query('Select * From '.$db_name.'recname Order BY Year desc, Course');
		if(!mysql_error())
		while($object = mysql_fetch_object($results))
		$rows[] = array(l($object->RecFile,'ages/'.$object->Record,''),$object->Descript,Course(1,$object->Course),$object->Year,$object->Flag);
		$output.= "<div style='width:100%;float: left'><br>".l('Season records breakers','seasons');
		if($_GET['ss']==$seas)
		$output.="<br/>".l('Latest Records Breakers','latest','');
		$output.="<table width='100%' border='0' cellspacing='0' cellpadding='0' style='border-style: solid; border-width: 0px'><tr valign='top'><td>".theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper','align'=>'left'),null)."</td><td width='170px'>".$banner160_600."</td></tr></table>";
		$output.='</div>';
		
		
		//require('record_breakers.php');
		
	//	$output.="<div  style='text-align:center;float: right'><b>Congratulations to the following<br> Record Breakers</b>".block_prefanal_record_breakers().'</div>';
	}
	break;
	
}

render_page();

?>