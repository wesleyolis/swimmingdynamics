<?php
require('../../main_include.php');

   $page_exe = true;
   $fina_year = $running_config['fina_points_rank_year'];;
      
  
   $lsc = $config['lsc'];
   $cntry = $config['cntry'];
   $url_pref = (($cntry=='')?'All':$cntry);
   $confirm = $config['rankcon'];

   
   $pref_head='';
   if($cntry!='')
     $pref_head.=$cntry.' ';
   if($lsc!='')
   {
   $pref_head.= (($pref_head !='')?' - ':'');
     $pref_head.=$lsc.' ';
   }
   
   
   if(isset($_GET['lsc']) == true)
   if($_GET['lsc']!=$cntry & $_GET['lsc']!=null & $_GET['lsc']!='All' & $_GET['lsc']!=$lsc)
   {
	   $pref_head.= (($pref_head !='')?' - ':'');
     $pref_head.=$_GET['lsc'].' ';
   }
   
   $update = $config['ranking_update'];
   $period = $config['ranking_period'];
   $rank_fina = $config['rank_fina_multi_seasons'];
   
   $type = Array('indi_points'=>'Individual Points','team_points'=>'Team Points','time'=>'Time','fina'=>'Fina');

   setseasons_breadcrumb(null);

   //cehck rankigns are being updateed.
   
	   //check rankings are in the proces of being update, then redirect updating page.
	   if(($running_config == null && $page_file != 'rankings_updating.php') || ($running_config['ranking_updating'] == true && $page_file != 'rankings_updating.php'))
	   {
		   header("Cache-Control: max-age=-1, no-store"); 
		   drupal_goto('rankings_updating.php',null,true);
	   }	
   
	//check for predicative ranking individual parameters and combine them
	
   		$ranking_on_date = $_GET['d'];
	$rankings_predicative = false;
	//check for redirection
	if($ranking_on_date == null)
	{	//if their is no ranking date use the general ranking date, this keeps pages unic and prevent user being served a catch copy of a page
		$ranking_on_date = $running_config['ranking_last_update'];
		//check is archived then their is no predicative rankings
	}
	else
	{	
		//can be repalce by date_parse_formate in php5.3
		
		$B_date = explode('-',$ranking_on_date);
		$ranking_on_date_d = mktime(0, 0, 0, $B_date[1], $B_date[2], $B_date[0]);
		
		$B_date = explode('-',$running_config['ranking_last_update']);
		$ranking_last_update_d = mktime(0, 0, 0, $B_date[1], $B_date[2], $B_date[0]);

		//check ranking is old date in url, or if its archive then can only be on date, as their is no predicative rankings.
		if($ranking_on_date_d <	$ranking_last_update_d || ($running_config['ranking_type']=='archive' && $ranking_on_date_d != $ranking_last_update_d))
		{
			//if ther submited date rankings is older than current ranking, redicte the user to the lastest rankings date with the same url parameters
			$_GET['d'] = $running_config['ranking_last_update'];
			$param = '';
			foreach($_GET as $key => $val)
			{
				$param.= (($param=='')?'':'&').$key.'='.$val;
			}
			if($page_file == 'ranking_pred.php')
				drupal_goto('index.php',$param,false);
			else
				drupal_goto($page_url.$page_file,$param,false);
		}
		else
		{
			
			if($ranking_on_date_d == $ranking_last_update_d)
			{
				$rankings_predicative = false;
			}
			else
			{
				$rankings_predicative = true;
			}
		}
	}
	
	
	//Limit search engines to only index relavant catagories.
	$index_page = true;
	if(isset($_GET['lo']) || isset($_GET['hi']))
	{
		$selected_age_group =($_GET['lo']*100) + $_GET['hi'];
		$search_engine_allowable_groups = Array(99,8,909,910,1010,1011,1111,1112,1212,1213,1313,1314,1414,1415,1515,1516,1616,1617,1718,1818,1899,99,011,1114,1516,1699,1718,1930,3150,5099);
		if(in_array($selected_age_group,$search_engine_allowable_groups))
			$index_page = true;
		else
			$index_page = false;
	}
	if($rankings_predicative == true)
	{
		$pref_head.='Predictive  ';
		$index_page = false;
		
	}
	if($index_page)
	$meta_tags.='<meta name="robots" content="index, follow">';
	else
	$meta_tags.='<meta name="robots" content="noindex, nofollow">';
	
	$meettype = $config['rank_time_meet_filter'];
	$meettype_fina = $config['rank_fina_meet_filter'];
	
	//render the options leading up to rankings
	

	$curr_param = '';
	foreach($_GET as $key => $val)
	{
		if($key != 'ss' && $key != 'db' && $key != 'op')
		$curr_param.= (($curr_param=='')?'':'&').$key.'='.$val;
	}
	

   
?>