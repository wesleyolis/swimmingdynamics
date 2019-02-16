<?php
	if(arg(7)!=null)
	$age_groups = Array(99,011,1114,1516,1699,1718,1930,3150,5099);
	else
	$age_groups = Array(99,8,909,910,1010,1011,1111,1112,1212,1213,1313,1314,1414,1415,1515,1516,1616,1617,1718,1818,1899);
	
	drupal_set_title($pref_head.$type[arg(3)].' Rankings - Age Group & Gender'."<br/><small>".$heading.' '.Course(1,arg(6)).' </small>');
	setseasons_breadcrumb($breadcrumb);
	$headers[] = array('data' => t('Female'), 'width' => '100px');
	$headers[] = array('data' => t('Male'), 'width' => '100px');
	$headers[] = array('data' => t('Mixed'), 'width' => '100px');
	foreach($age_groups as $gp)
	  {
	     $rows[] = array(l(Age($gp),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/female/'.$gp),l(Age($gp),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/male/'.$gp),l(Age($gp),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/mixed/'.$gp));
	  }

	$output.= theme_table($headers, $rows,null,null);
?>