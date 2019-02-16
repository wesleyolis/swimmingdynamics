<?php

	require('ranking_config.php');
		    

	$rank_days_before = $config['ranking_days_before'];
	
	drupal_set_title((($_GET['sanc']!=null & $cntry !=null)?$cntry:'').' Rankings Included Meets');
	$meta_tags.='<meta name="robots" content="noindex, nofollow">';
	$headers[] = array('data' => t('Meet'), 'style' => 'width:30em;');
	$headers[] = array('data' => t('Type'), 'style' => 'width:1em;');
	$headers[] = array('data' => t('Sanc'), 'style' => 'width:1em;');
	$headers[] = array('data' => t('Start date'), 'style' => 'width:5.5em;');
	$headers[] = array('data' => t('End date'), 'style' => 'width:5.5em;');
	$headers[] = array('data' => t('Course'), 'style' => 'width:2em;');
	$headers[] = array('data' => t('Location'));
	// order by m.Start DESC
	
	$rank_from_option = $config['rank_from'];
	
	$Sd = $config['ranking_dd'];
	$Sm = $config['ranking_mm'];
	$from_date = $_GET['ss'].'-'.$Sm.'-'.$Sd;
	
	$query = "select SQL_CACHE DISTINCT IF(e.Meet Is Null,1,0) as results, m.Meet, m.MName, m.Start, m.End,m.type,m.Sanction, m.Course, m.Location  from ".$db_name."meet m left join ".$db_name."meet_sanctions as sm on (m.Meet=sm.meet) left join ".$db_name."mtevent e on (m.Meet=e.Meet) where m.Start is not null and m.End is not null  ".(($_GET['sanc']=='ALL')?'':" and sm.abbr='".inj_str($_GET['sanc'])."' ").(($confirm!='')?' and sm.include=1':'')." ".(($_GET['course']=='ALL' || $_GET['course']==null)?'':" and instr(m.Course,'".inj_str($_GET['course'])."')>0").' '." and (DATEDIFF(m.End,'".$running_config['ranking_last_update']."')<=-".$rank_days_before." )".(($rank_from_option=='y')?"and (DATEDIFF(m.End,'".$from_date."')>=0 )":'')." and e.Meet Is not Null order by m.Start desc";
	
	$result = db_query($query);
	if(!mysql_error())
	while ($object = mysql_fetch_object($result))
	{
	        $link = ($object->results==0)?'../meets/events.php':'../meets/meets_info.php';

	       $class = ($object->results==0)?' green':' red';

	       $rows[] = array('class'=>$class,'data' => array(l2($object->MName.(($object->results==0)?' Results':' info'),'m='.$object->Meet.(($object->results==0)?'&age=All&gen=All':''), $link),$object->type,get_sanc($object->Sanction),get_date($object->Start), get_date($object->End), $object->Course, $object->Location));
	}
	$output= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
			 
	render_page();
?>