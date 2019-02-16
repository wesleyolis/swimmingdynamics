<?php
require('../../main_include.php'); 

$meta_tags.='<meta name="robots" content="index, follow">';

			    
			    $cntry = $config['cntry'];

			    drupal_set_title((($_GET['sm']!='ALL')?$_GET['sm']:(($_GET['cntry']!=null & $cntry !=null)?$cntry:'')).' Meets '.$_GET['ss'].'-'.($_GET['ss']+1));
			    setseasons_breadcrumb(array(l2('Meets','','index.php')));
			    //$output.= highlight_js('meets');
			    //$output.= drupal_get_form('perfanal_meet_filters_form');

				$headers[] = array('data' => t('Meet'), 'style' => 'width:30em;');
				$headers[] = array('data' => t('Type'), 'style' => 'width:1em;');
				$headers[] = array('data' => t('Sanc'), 'style' => 'width:1em;');
				$headers[] = array('data' => t('Start date'), 'style' => 'width:5.5em;');
				$headers[] = array('data' => t('End date'), 'style' => 'width:5.5em;');
				$headers[] = array('data' => t('Course'), 'style' => 'width:2em;');
				$headers[] = array('data' => t('Location'));
			    // order by m.Start DESC

			       $rank_from_option = $config['rank_from'];
	     
			       $Sd = $config['ranking_dd'];
			       $Sm = $config['ranking_mm'];
			       
			      
	
			    $query = "select SQL_CACHE DISTINCT IF(e.Meet Is Null,1,0) as results, m.Meet, m.MName, m.Start, m.End,m.type,m.Sanction, m.Course, m.Location  from ".$db_name."meet m left join ".$db_name."meet_sanctions as sm on (m.Meet=sm.meet) left join ".$db_name."mtevent e on (m.Meet=e.Meet) where 1=1 ".(($_GET['sm']!='All' && $_GET['sm']!=null)?" and sm.c=1 and sm.abbr='".inj_str($_GET['sm'])."'":"")." ".(($_GET['type']!='All' && $_GET['type']!=null)?" and sm.c=1 and m.type='".inj_str($_GET['type'])."' ":"")." order by m.Start desc";
		
			    $output.=$query;
			    $result = db_query($query);
			    if(!mysql_error())
			     while ($object = mysql_fetch_object($result))
			      {
				 $link = ($object->results==0)?'events.php':'meets_info.php';

				 $class = ($object->results==0)?' green':' red';

				 $rows[] = array('class'=>$class,'data' => array(l2($object->MName.(($object->results==0)?' Results':' info'),'m='.$object->Meet.(($object->results==0)?'&age=All&gen=All':''), $link),$object->type,get_sanc($object->Sanction),get_date($object->Start), get_date($object->End), $object->Course, $object->Location));
			      }
			    // $output.= drupal_get_form('perfanal_meets_filters_form');
			  
			    //filters

			    	
				
				
				$query="Select SQL_CACHE distinct c.abbr,c._desc From ".$db_name."code as c inner join ".$db_name."meet as  m on (c.abbr=m.type) inner join ".$db_name."meet_sanctions as sm on (m.Meet=sm.meet) where 1=1 ".(($_GET['sm']!='All')?" and sm.c=1 and sm.abbr='".inj_str($_GET['sm'])."'":"")." and c.TYPE=3 order by c.abbr";
				
				$output.=$query;
				
				$results = db_query($query);
				if(!mysql_error())
				if(mysql_num_rows($results) > 1)
				{
				$output.="<table class='no_print'><tr><td><b>Meet Type </b></td><td></td><td>";
				$output.="<form action='type.php'  method='get'>";
				$output.="<input type='hidden' name='db' value='".$_GET['db']."'>";
				$output.="<input type='hidden' name='ss' value='".$_GET['ss']."'>";
				if($_GET['sm'])
				$output.="<input type='hidden' name='sm' value='".$_GET['sm']."'>";
				$output.="<select name='type'>";
					$output.="<option value='All' ".($_GET['type']==''?"selected='selected'":'').">All Types</option>";
				
				while($object = mysql_fetch_object($results))
				$output.="<option value='".$object->abbr."' ".($_GET['type']==$object->abbr?"selected='selected'":'')." >".$object->abbr.' - '.$object->_desc."</option>";
				$output.="</select>";
				//$output.="<span><input type='checkbox' name='op' value='ON'>Include Open Events</span>";
				
				$output.="&nbsp;&nbsp;<input type='submit' value='Filter'/></td></tr></table>";
				}
				$output.=$banner768_160.'<br>';
			    
				$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
			 
		    render_page();
?>