<?php

require('ranking_config.php');

	

   if(isset($_GET['type']) == false)
   {
	//determine page jumping of selection that have been present
	
	if($lsc !='')
	{
		$page_param_gen = '&lsc='.$lsc;
		$page_link_gen = 'rankings_geo.php';
		
		$page_param_fina = '&lsc='.$lsc;
		$page_link_fina = 'rankings_geo.php';
		
		
		if($meettype!='' & $meettype!='LSC')
		{
			$page_param_gen.= '&sanc='.$meettype;
			$page_link_gen = 'rankings_course.php';	
		}
	
		if($meettype_fina!='' & $meettype_fina!='LSC')
		{
			$page_param_fina.= '&sanc='.$meettype_fina;
			$page_link_fina = 'rankings_course.php';
		}
		
		if($meettype=='LSC')
		  {
			  if($cntry=='' || $lsc != $cntry)
			  {
			     //goto the same lsc for geo too
			     $page_param_gen.= '&sanc='.$lsc;
			     $page_link_gen = 'rankings_course.php';
			  }
			  else
			  {
			     $page_param_gen.= '&sanc=ALL';
			     $page_link_gen = 'rankings_course.php';	  
			  }
		  }
		  
		 if($meettype_fina=='LSC')
		  {
			  if($cntry=='' || $lsc != $cntry)
			  {
			     //goto the same lsc for geo too
			     $page_param_fina.= '&sanc='.$lsc;
			     $page_link_fina = 'rankings_course.php';
			  }
			  else
			  {
			     $page_param_fina.= '&sanc=ALL';
			     $page_link_fina = 'rankings_course.php';	  
			  }
		  }
	}
	else
	{
		$page_param_gen = '';
		$page_link_gen = 'rankings_lsc.php';
		
		$page_param_fina = '';
		$page_link_fina = 'rankings_lsc.php';
	}

	


	//end page jumping
	
	drupal_set_title($pref_head." Rankings Categories");
	$output='<br>'.l2('Individual Points','type=indi_points&d='.$ranking_on_date.$page_param_gen,$page_link_gen ).'<br><br>';
	if($rankings_predicative==false)
	$output.=l2('Team Points','type=team_points&d='.$ranking_on_date.$page_param_gen,$page_link_gen ).'<br><br>';
	$output.=l2('Times','type=time&d='.$ranking_on_date.$page_param_gen,$page_link_gen ).'<br><br>';
	$output.=l2('Fina - '.$running_config['fina_points_rank_year'].' Base Times' ,'type=fina&d='.$ranking_on_date.$page_param_fina,$page_link_fina ).'<br>Allows performance comparison across strokes<br>and sprinters against long distance athletes.<br><br>';
	if($rankings_predicative==false && $running_config['ranking_type']!='archive')
	$output.=l2('Predictive  Rankings','','rankings_pred.php')."<br>Allows you to select a date at which swimmers ages are calculated to see how the age group rankings are influenced by your competitors' age-out of and age-in to your age group.";
   }

render_page();
?>