<?php

 $lsc = variable_get('perfanal_lsc', '');
   $cntry = variable_get('perfanal_cntry', '');
   
   
   
	$items[] = array('path' => 'meets',
			 'title' => t('Meets'),
			 'callback' => 'perfanal_meets',
			 'access' => user_access('access performance analysis'),
			 'type' =>MENU_NORMAL_ITEM,
			 'weight' => 1);
	$title=(($cntry!='')?$cntry:'').(($cntry!='' & $lsc!='')?'-':'').(($lsc!='')?$lsc:''); 
			 
	$items[] = array('path' => 'ranking',
			 'title' => t($title.' Rankings'),
			 'callback' => 'perfanal_ranking',
			 'access' => user_access('access performance analysis'),
			 'type' =>MENU_NORMAL_ITEM,
			 'weight' => 2);

	$items[] = array('path' => 'standards',
			 'title' => t('Standards'),
			 'callback' => 'perfanal_standards',
			 'access' => user_access('access performance analysis'),
			 'type' =>MENU_NORMAL_ITEM,
			 'weight' => 4);

	$items[] = array('path' => 'athlete',
			 'title' => t('Athlete Times'),
			 'callback' => 'perfanal_athlete',
			 'access' => user_access('access performance analysis'),
			 'type' =>MENU_NORMAL_ITEM,
			 'weight' => 3);

	$items[] = array('path' => 'records',
			 'title' => t('Records'),
			 'callback' => 'perfanal_records',
			 'access' => user_access('access performance analysis'),
			 'type' =>MENU_NORMAL_ITEM,
			 'weight' => 5);
	
/*
	$seasons = seasons();
	
	
			 
	foreach ($seasons as $season => $value)
	  {

		  $items[] = array('path' => 'records/'.$season.'/break',
			 'title' => t('Records Breakers'),
			 'callback' => 'perfanal_record_break',
			 'access' => user_access('access performance analysis'),
			 'type' =>MENU_CALLBACK,
			 'weight' => 5);
		
	     $items[] = array('path' => 'meets/'.$season,
			      'title' => t('Meets '.$value),
			      'callback' => 'perfanal_meets',
			      'access' => user_access('access performance analysis'),
			      'weight' => ('-1.'.$season));

			     
			   
	     $items[] = array('path' => 'ranking/'.$season,
			      'title' => t($title.' Rankings '.$value),
			      'callback' => 'perfanal_ranking',
			      'access' => user_access('access performance analysis'),
			      'weight' => ('-2.'.$season));

	     $items[] = array('path' => 'athlete/'.$season,
			      'title' => t('Athlete Times '.$value),
			      'callback' => 'perfanal_athlete',
			      'access' => user_access('access performance analysis'),
			      'weight' => ('-3.'.$season));

	     $items[] = array('path' => 'standards/'.$season,
			      'title' => t('Standards '.$value),
			      'callback' => 'perfanal_standards',
			      'access' => user_access('access performance analysis'),
			      'weight' => ('-4.'.$season));

	     $items[] = array('path' => 'records/'.$season,
			      'title' => t('Records '.$value),
			      'callback' => 'perfanal_records',
			      'access' => user_access('access performance analysis'),
			      'weight' => ('-5.'.$season));
		

	  }
*/
	$items[] = array('path' => 'fina',
			 'title' => t('Fina Points'),
			 'callback' => 'perfanal_fina',
			 'access' => user_access('access performance analysis'),
			 'weight' => 6);
		
	


?>