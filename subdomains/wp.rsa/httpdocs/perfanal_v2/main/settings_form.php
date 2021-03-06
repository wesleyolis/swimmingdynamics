<?php
header("Cache-Control: max-age=-1, no-store"); 
  $season = get_seasons(true);
   $tm4db = variable_get('perfanal_database', 'perfanal');

   $confirm = variable_get('perfanal_rankcon', 'y');
   
   $form['perfanal_database'] = array('#type' => 'textfield', '#size' => 40, '#max_length' => 40,
				      '#default_value' => variable_get('perfanal_database', 'perfanal'), '#title' => t('Database'),
				      '#description' => t('Specify the name of the database to be used in prefixing all calls to the performance analysis tables.'));

   $form['perfanal_ranking_update'] = array('#title'=>'Rankings Update','#type' => 'select', '#options' => Array('M'=>'Monthly','W'=>'Weekly'), '#default_value' => variable_get('perfanal_ranking_update', 'W'));

   $form['perfanal_ranking_period'] = array('#type' => 'textfield', '#size' => 5, '#max_length' => 1,
					    '#default_value' => variable_get('perfanal_ranking_period', '2'), '#title' => t('Weekly Period'),
					    '#description' => t('Weekly Period to update rankings,does not affect Monthly thought'));
					    

   $form['perfanal_ranking_dd'] = array('#type' => 'textfield', '#size' => 3, '#max_length' => 2,
					'#default_value' => variable_get('perfanal_ranking_dd', '01'), '#title' => t('Rankings Season DD'));

   $form['perfanal_ranking_mm'] = array('#type' => 'textfield', '#size' => 3, '#max_length' => 2,
					'#default_value' => variable_get('perfanal_ranking_mm', '05'), '#title' => t('Rankings Season MM'),
					'#description' => t('Used to signify the start and end date of a new season'));

					
   $form['perfanal_ranking_days_before'] = array('#type' => 'textfield', '#size' => 3, '#max_length' => 2,
					'#default_value' => variable_get('perfanal_ranking_days_before', '2'), '#title' => t('Rankings Meet End * Days Before'),
					'#description' => t('Meets will only be include in rankings if there end date is * days before the current rankings date, this allows TM Managers to correct error found.Default is 2 days'));
					
	 $options['y']='Yes';
	 $options['']='No';				
 $form['perfanal_rank_from'] = array('#type' => 'select', '#options' => $options,
				     '#default_value' => variable_get('perfanal_rank_from', 'y'), '#title' => t('Ranking Include Meets From this Season only'),
				     '#description' => t("Meets before the being of current season month and day will note be include in rankings."));
	/*
	$selected = (get_cur_seasons()-1);
	  foreach (seasons() as $key => $value)
	     {
		if($key == $selected)
		{
		  $options['y']='Yes';
		  break;
		}
	     }
	     
	 $options['']='No';*/
				     
 $form['perfanal_rank_fina_multi_seasons'] = array('#type' => 'select', '#options' => $options,
				     '#default_value' => variable_get('perfanal_rank_fina_multi_seasons', ''), '#title' => t('Fina Rankings - Age over two seasons.'),
				     '#description' => t("Meets scantion as 'FNA' will be include in the rankings. Athelte's age will be calculated as the age that pretains to 1 full year of times over a 2 seasons period, current and previius season.The previous best times of the last season must also be include in the sanctioning and this data base."));

  $result = db_query("show tables like '".$tm4db."meet\_____'");
  // $result = db_query("show tables from ".$tm4db." like 'meet\_____'");
   $seas[''] = 'Latest';
   while ($row_data = mysql_fetch_array($result))
     {
	$year = substr($row_data[0], strlen($row_data[0])-4, 4);
	if ($year != "")
	  {
	     $seas[$year] = $year .'-'.($year+1);
	     $seas_setttings [$year]  = $year .'-'.($year+1);
	  }
     }

   $form['perfanal_season'] = array('#type' => 'select', '#options' => $seas,
				    '#default_value' => variable_get('perfanal_season', ''), '#title' => t('Default Season'),
				    '#description' => t("Spesify the default Season to be used."));
				    

	/*$result = db_query("SELECT DISTINCT Years From {fina_points}");

	for($i=0;$object = db_fetch_object($result);$i++)
	$rows[$i] = $object->Years;
	$form['ranking_fina'] = array('#title'=>'Rankings Fina, Base Times of year','#type' => 'select', '#options' => $rows, '#default_value' => variable_get('perfanal_ranking_fina', '2004'));
	*/
   //$form['club'] = array('#type' => 'select', '#options' => $club_list, '#default_value' => arg(2));

   $form['perfanal_display_breakers'] = array('#type' => 'textfield', '#size' => 5, '#max_length' => 2,
					      '#default_value' => variable_get('perfanal_display_breakers', '20'), '#title' => t('Records Breakers'),
					      '#description' => t('Specify the number of days ago, that records will be classified as broken and promoted to front page. '));

   $form['perfanal_min_display'] = array('#type' => 'textfield', '#size' => 5, '#max_length' => 3,
					 '#default_value' => variable_get('perfanal_min_display', '20'), '#title' => t('Minimum Display'),
					 '#description' => t('Specify the minimum about of (results/events) to display on a page. 0 to disable.'));

  
   $form['perfanal_rankcon'] = array('#type' => 'select', '#options' => $options,
				     '#default_value' => $confirm, '#title' => t('Ranking Meet Confirm'),
				     '#description' => t("Use the santion field as confirmation of weather a meet is excluded from rankings."));
	$options=array();							     
   $options['R']='as in Result';
   $options['T1']='Athlete Team 1';
   $options['T2']='Athlete Team 1/2';
   $options['T3']='Athlete Team 1/2/3';
   $form['perfanal_rank_team'] = array('#type' => 'select', '#options' => $options,
				     '#default_value' => variable_get('perfanal_rank_team', 'R'), '#title' => t('Ranking Team field'),
				     '#description' => t("Spesify how to use the Country and LSC team filtering, note using multiply team fields
				     result in degredation in preformance"));

   $form['perfanal_cntry'] = array('#type' => 'textfield', '#size' => 5, '#max_length' => 3,
				   '#default_value' => variable_get('perfanal_cntry', 'RSA'), '#title' => t('Country Filter'),
				   '#description' => t("Specify the defalt Country Filter to be used for rankings e.g 'RSA', Leave blank for all"));

   $form['perfanal_cntry_desc'] = array('#type' => 'textfield', '#size' => 35, '#max_length' => 30,
					'#default_value' => variable_get('perfanal_cntry_desc', 'Republic of South Africa'), '#title' => t('Country Description'),
					'#description' => t("The Country description"));

   $form['perfanal_lsc'] = array('#type' => 'textfield', '#size' => 5, '#max_length' => 3,
				 '#default_value' => variable_get('perfanal_lsc', ''), '#title' => t('LSC Filter'),
				 '#description' => t("Specify the defalt LSC Filter to be used for rankings e.g 'WP', Leave blank for all"));

		/*
	$form['perfanal_type'] = array('#type' => 'textfield', '#size' => 5, '#max_length' => 3,
		'#default_value' => variable_get('perfanal_type', ''), '#title' => t('Meet Type Filter'),
		'#description' => t("Specify the defalt Meet Type Filter to be used for rankings e.g 'OF' or 'ALL', Leave blank for all"));*/
	/*
	$form['perfanal_meet_type_all'] = array('#type' => 'textfield', '#size' => 20, '#max_length' => 35,
		'#default_value' => variable_get('perfanal_meet_type_all', ''), '#title' => t('Meet Type All Desciprtion'),
		'#description' => t("Specify the description for the meet type all"));
	*/
	
	/*
	$form['perfanal_teams_limit'] = array('#type' => 'textfield', '#size' => 3, '#max_length' => 2,
					'#default_value' => variable_get('perfanal_teams_limit', '02'), '#title' => t('Limit Scoring Teams'),
					'#description' => t("Used to limit team scoring to ## athlete's per team"));
*/
//instr(m.abbr,".(($confirm=='')?'c.abbr':"CONCAT(c.abbr,',')").")>0

foreach( $seas_setttings as $key =>$s)
{

   $results = db_query("Select distinct c.ABBR,c._DESC  From ".$tm4db."code_".$key." as c inner join ".$tm4db."meet_sanctions_".$key." as m ON (m.abbr=c.abbr) inner join ".$tm4db."result_".$key." as r on (m.meet=r.meet) Where m.c>1 and  TYPE=3 and m.include=1  order by abbr");
   $club_list='';
   $club_list[''] = 'none';
   $club_list['LSC'] = 'LSC - Use Select LSC Filter';
   $club_list['ALL'] = 'All';
   while($object = db_fetch_object($results))
     if($object->ABBR !=null)
       $club_list[$object->ABBR] = ($object->ABBR.' - '.$object->_DESC);
  
  
  $form['perfanal_'.$key.'_type'] = array('#type' => 'select', '#options' => $club_list,
				  '#default_value' => variable_get('perfanal_'.$key.'_type', ''), '#title' => t($s.' Meet Type Filter - Time,Points'),
				  '#description' => t("Specify the Meet Type Filter to be used for rankings e.g 'OF' or 'ALL'"));
				  
 $form['perfanal_'.$key.'_type_fina'] = array('#type' => 'select', '#options' => $club_list,
				  '#default_value' => variable_get('perfanal_'.$key.'_type_fina', ''), '#title' => t($s.' Meet Type Filter - Fina'),
				  '#description' => t("Specify the Meet Type Filter to be used for rankings e.g 'OF' or 'ALL'"));
}
   $form['perfanal_pages'] = array('#type' => 'textfield', '#size' => 5, '#max_length' => 3,
				   '#default_value' => variable_get('perfanal_pages', '200'), '#title' => t('Pager Result'),
				   '#description' => t("Specify the number of rows to return in reports."));
				   
   $form['perfanal_ranking_timeout'] = array('#type' => 'textfield', '#size' => 3, '#max_length' => 2,
					'#default_value' => variable_get('perfanal_ranking_timeout', '10'), '#title' => t('Script Time Outs'),
					'#description' => t("Maxium amount of time it can take Rankings or Optimizing Script to complete"));



?>