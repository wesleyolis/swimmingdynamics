<?php
$option = 'graphs';
require('../../../main_include.php');
require('../heading.php');

$js_include.="<script type='text/javascript' src='".$app_relative."js/rgraph_v1/RGraph.common.core.js'></script>";
$js_include.="<script type='text/javascript' src='".$app_relative."js/rgraph_v1/RGraph.line.js'></script>";
$js_include.="<script type='text/javascript' src='".$app_relative."js/rgraph_v1/RGraph.common.zoom.js'></script>";
$js_include.="<script type='text/javascript' src='".$app_relative."js/rgrapher_v1.js'></script>";

{
	drupal_set_title((($_GET['dis']=='All' && $_GET['st'] =='All')?'All':(($_GET['st']!='All')?Stroke($_GET['st']):$_GET['dis'].'m ')).' Fina Graphs of Results - '.$heading);
	
		  $header[] = array('data' => 'Avg-Fina', 'width' => '70px');
		  $header[] = array('data' => 'Events', 'width' => '70px');
		  $header[] = array('data' => 'Date', 'width' => '90px');
		  		  		  
		  
		 
		  $query= "select round(avg(r.fina),0) as fina_avg, count(*) as events, m.Start from ".$db_name."result as r, ".$db_name."meet as m where r.ATHLETE=".inj_int($_GET['ath'])." and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.fina !=0 ".($_GET['st']=='All'?'':' and r.stroke='.inj_int($_GET['st']))." ".($_GET['dis']=='All'?'':' and r.distance='.inj_int($_GET['dis']))." group by m.Start order by m.start";
		  
		
		  $result = db_query($query);
		 
		    	
		       $graph_data ='';
		       $graph_hdrs='';
		       if(!mysql_error())
		       while ($object = mysql_fetch_object($result))
			 {
			    //				print_r($object);

			     
			    $start_date = explode("-", $object->Start);
			    $date = Date('d/m/Y', mktime(0, 0, 0, intval($start_date[1]), intval($start_date[2]), intval($start_date[0])));
			    
			    $rows[] = array($object->fina_avg, $object->events, $date);
			    $graph_hdrs.= "'".$date."',";
			    $graph_data.= $object->fina_avg.',';

		       
		    }  
		    $graph_info = '{\'type\':\'num\',\'clabels\':['.$graph_hdrs.'null],\'plot\':[';
		       $graph_info.= (($graph_data!='') ? '['.$graph_data.'null]' : '[null]');
			 $graph_info.="]};";
			 
		  if($graph_hdrs!='')
		   $rows[] = array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><canvas class='rgraph wes'  id=\"".$graph_info."\" width='720' height='230' style='border: 1px solid #000000;background-color: #ffffff;'>[Please wait...]<br> You may need to upgrade your browser to more modern browser</canvas></td></tr></table>", 'colspan' => 3,'align'=>'center'));
		    $output.= '<br><div style="font-weight: bold;">';
		   if($_GET['dis']!='All')
		    $output.= $_GET['dis'].'m ';
		   if($_GET['st']!='All')
		    $output.=Stroke($_GET['st']);
		    $output.='</div><hr>';
		       $output.= theme_table($header, $rows,null,null).'<br>';
		       
		       $output.='<br>Power by Rgraph.';
		      
		      
	       }
	       
	       render_page();
?>