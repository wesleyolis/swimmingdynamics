<?php

	require('ranking_config.php');

	
	
	drupal_set_title($pref_head.$type[$_GET['type']].' Rankings - Meet Sanction');
	$breadcumb[] = l2('Ranking Catagories','','index.php');
	if($lsc=='')
	{
		$breadcumb[] = l2('LSC Filter',substr($curr_param,0,strrpos($curr_param,'&')),'rankings_lsc.php');
	}
	setseasons_breadcrumb($breadcumb);

	$confirm = $config['rankcon'];

  $output.='</br>Please select the category of meets result to use in the computing of the rankings.';
	
	   $headers[] = array('data' => t('Abbr'), 'style' => 'width:2em;');
	   $headers[] = array('data' => t('Description'), 'style' => 'width:20em;');
	
	   $max_sanctions = $running_config['max_sanctions'];
	   for($i=2;$i<=$max_sanctions;$i++)
	   $Where = ' or c.tindex=r.Rtype'.$i;
	   $results = db_query("Select SQL_CACHE distinct c.ABBR,c._DESC  From ".$db_name."code as c inner join ".$db_name."meet_sanctions as m ON (m.abbr=c.abbr) inner join ".$db_name."result as r on (m.meet=r.meet) Where m.c>1 and (c.tindex=r.Rtype ".$Where.") and  TYPE=3 ".(($confirm=='')?'':" and m.include=1")."  order by abbr");
	
	   $rows[] = array(l2('ALL',$curr_param.'&sanc=ALL','rankings_course.php'), 'All Result, Un-Official' );
	   if(!mysql_error())
	   while($object = mysql_fetch_object($results))
	     if($object->ABBR !=null)
	       $rows[] = array(l2($object->ABBR,$curr_param.'&sanc='.$object->ABBR,'rankings_course.php'),$object->_DESC);
	
	   $output.=theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);

	render_page();

?>