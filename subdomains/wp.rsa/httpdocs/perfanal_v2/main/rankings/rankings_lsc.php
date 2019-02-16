<?php

	require('ranking_config.php');

	$page_param.= '';
	$page_link = 'rankings_geo.php';	
	
	if($meettype!='' && $meettype!='LSC' && $_GET['type']!='fina')
	{
		$page_param.= '&sanc='.$meettype;
		$page_link = 'rankings_course.php';	
	}

	if($meettype_fina!='' && $meettype_fina!='LSC' && $_GET['type']=='fina')
	{
		$page_param.= '&sanc='.$meettype_fina;
		$page_link = 'rankings_course.php';
	}
	
	if(($meettype=='LSC' && $_GET['type']!='fina') || ($meettype_fina=='LSC' && $_GET['type']=='fina'))
	  {
		  if($cntry=='' || $lsc != $cntry)
		  {
		     //goto the same lsc for geo too
		     $page_param.= '&sanc='.$lsc;
		     $page_link = 'rankings_course.php';
		  }
		  else
		  {
			  
		     $page_param.= '&sanc=ALL';
		     $page_link = 'rankings_course.php';	  
		  }
	  }
	
	
	drupal_set_title($pref_head.$type[$_GET['type']].' Rankings - LSC Filter');
	setseasons_breadcrumb(array(l2('Ranking Catagories','','index.php')));

	$headers[] = array('data' => t('LSC'), 'style' => 'width:4em;');
	$headers[] = array('data' => t('Description'), 'style' => 'width:20em;');

	$results = db_query("Select SQL_CACHE distinct t.lsc,c._desc From ".$db_name."team as t left join ".$db_name."code as c on (t.lsc = c.abbr and c.type=3) where t.lsc !='' ".(($cntry=='')?'':"and t.tcntry ='".$cntry."'" ).((($meettype=='LSC' && $_GET['type']!='fina') || ($meettype_fina=='LSC' && $_GET['type']=='fina'))?' and t.lsc=c.abbr':'').' order by LSC');
	  if($lsc=='' & $cntry!='')
	    $rows[] = array(l2($url_pref,$curr_param.'&lsc='.$url_pref.$page_param,$page_link),$config['cntry_desc']);

	if(!mysql_error())
	while($object = mysql_fetch_object($results))
	  if($cntry != $object->lsc)
	    $rows[] = array(l2($object->lsc,$curr_param.'&lsc='.$object->lsc.$page_param,$page_link),$object->_desc);

	$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);

	render_page();

?>
