<?php
 drupal_set_title((($cntry!='')?$cntry:'').((arg(4)=='')?'':(($cntry!='')?' - ':'').arg(4))." Teams Information");
	  $output.="<br><div class='tabs'><ul class='tabs primary'>";
	  $output.="<li ".((arg(3)=='include')?"class='active'":'').">".l('Include','ranking/'.arg(1).'/teams/include/'.arg(4))."</li>";
	  $output.="<li ".((arg(3)!='include')?"class='active'":'').">".l('Exclude','ranking/'.arg(1).'/teams/exclude/'.arg(4))."</li>";
	  $output.="</ul></div>";
	  if(arg(3)=='include')
	    {
	       $query = "Select distinct tname,tcode,lsc,tcntry From ".$tm4db.".team_".$season." where 1=1 ".(($cntry=='')?'':"and tcntry ='".$cntry."'").' '.((arg(4)=='')?'':"and lsc ='".arg(4)."'").' order by tcntry,lsc,tcode';
	       $results = db_query($query);

	    }
	  else if (arg(3) == 'exclude')
	    {
	       $query = "Select distinct tname,tcode,lsc,tcntry From ".$tm4db.".team_".$season." where not( 1=1  ".(($cntry=='')?'':"and tcntry ='".$cntry."'").' '.((arg(4)=='')?'':"and lsc ='".arg(4)."'").') order by tcntry,lsc,tcode';
	       $results = db_query($query);
	    }

	  $headers[] = array('data' => t('Name'), 'width' => '200px');
	  $headers[] = array('data' => t('Code'), 'width' => '60px');
	  $headers[] = array('data' => t('LSC'), 'width' => '40px');
	  $headers[] = array('data' => t('Country'), 'width' => '40px');

	  while($object = db_fetch_object($results))
	    $rows[] = array($object->tname,$object->tcode,$object->lsc,$object->tcntry);

	  $output.= theme('table', $headers, $rows);

?>