<?php
	
	$headers[] = array('data' => t('Meet'), 'width' => '300px','field' => 'm.MName');
	$headers[] = array('data' => t('Type'), 'width' => '20px');
	$headers[] = array('data' => t('Sanc'), 'width' => '20px');
	$headers[] = array('data' => t('Start date'), 'width' => '130px','sort' => 'desc','field' => 'm.Start');
	$headers[] = array('data' => t('End date'), 'width' => '100px');
	$headers[] = array('data' => t('Course'), 'width' => '40px');
	$headers[] = array('data' => t('Location'));
	
	$query = "select SQL_CACHE DISTINCT m.Meet, m.MName, m.Start, m.End,m.type,m.Sanction, m.Course, m.Location  from ".$tm4db."meet_".$season." m where m.Start>'".$last_ranking."' ".tablesort_sql($headers);
	
	$result = db_query($query);
	while ($object = db_fetch_object($result))
	{
		$start_date = explode("-", $object->Start);
		
	 $link = 'ranking/'.arg(1).'/'.Date('Y-m-d', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
	
	
	 $rows[] = array('data' => array(l(t($object->MName), $link),$object->type,get_sanc($object->Sanction),get_date($object->Start), get_date($object->End), $object->Course, $object->Location));
	}
	$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
	?>
