<?php

	require('ranking_config.php');
	$meta_tags.='<meta name="robots" content="noindex, nofollow">';
	drupal_set_title((($cntry!='')?$cntry:'').(($_GET['lsc']=='')?'':(($cntry!='')?' - ':'').$_GET['lsc'])." Teams Information");
	
	$lsc_alias = $config['lsc_alias'];


		$filter="";		

		 if($_GET['lsc']!='')
		 {
			

			if(is_array($lsc_alias))
				{

					$output.="<br/>LSC Alias: ";
					$filter.=" and lsc in ( ";
					foreach ($lsc_alias as $value) {
						$filter.="'".$value."', ";
						$output.=$value." ,";
					}
					$filter.="'".inj_str($_GET['lsc'])."' ) ";
				
					$output.= "<br/>";
				}
				else{
					$filter.=(($_GET['lsc']=='')?'':"and lsc ='".inj_str($_GET['lsc'])."'");
				}			
		 }		 


	  $output.="<br><div class='tabs'><ul class='tabs primary'>";
	  $output.="<li ".(($_GET['include']=='true')?"class='active'":'').">".l2('Included','lsc='.$_GET['lsc'].'&include=true','rankings_teams.php')."</li>";
	  $output.="<li ".(($_GET['include']!='true')?"class='active'":'').">".l2('Excluded','lsc='.$_GET['lsc'].'&include=false','rankings_teams.php')."</li>";
	  $output.="</ul></div>";
	  
	  $cntry = $config['cntry'];

		 
	  
	  if($_GET['include'] == 'true')
	    {
	       $query = "Select distinct tname,tcode,lsc,tcntry From ".$db_name."team where 1=1 ".(($cntry=='')?'':"and tcntry ='".$cntry."'").' '.$filter.' order by tcntry,lsc,tcode';
	       $results = db_query($query);
	      
	    }
	  else if ($_GET['include'] == 'false')
	    {
	       $query = "Select distinct tname,tcode,lsc,tcntry From ".$db_name."team where not( 1=1  ".(($cntry=='')?'':"and tcntry ='".$cntry."'").' '.$filter.') order by tcntry,lsc,tcode';
	       $results = db_query($query);
	    }	   

	  $headers[] = array('data' => t('Name'), 'width' => '200px');
	  $headers[] = array('data' => t('Code'), 'width' => '60px');
	  $headers[] = array('data' => t('LSC'), 'width' => '40px');
	  $headers[] = array('data' => t('Country'), 'width' => '40px');

	  if(!mysql_error())
	  while($object = mysql_fetch_object($results))
	    $rows[] = array($object->tname,$object->tcode,$object->lsc,$object->tcntry);

	  $output.= '<br>'.theme_table($headers, $rows,null,null);
	  
	  render_page();

?>