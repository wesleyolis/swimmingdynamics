<?php
$option = 'graphs';
require('../../../main_include.php');
require('../heading.php');

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
			    $date = Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			    
			    $rows[] = array($object->fina_avg, $object->events, $date);
			    $graph_hdrs.= $date.'|';
			    $graph_data.= $object->fina_avg.'|';

		       
		    }  
		 
		  if($graph_hdrs!='')
		   $rows[] = array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><div class='cellrel'><div class='graphsback'>".theme_image("../../../images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image("../../../grapher_fina.php".($graph_hdrs!=''?'?headers='.$graph_hdrs.'&data1='.$graph_data:'?headers=&data1=0|'),null,null,null,false)."</div></td></tr></table>", 'colspan' => 3,'align'=>'center'));
		    $output.= '<br><div style="font-weight: bold;">';
		   if($_GET['dis']!='All')
		    $output.= $_GET['dis'].'m ';
		   if($_GET['st']!='All')
		    $output.=Stroke($_GET['st']);
		    $output.='</div><hr>';
		       $output.= theme_table($header, $rows,null,null).'<br>';
		       
		       
		      
		      
	       }
	       
	       render_page();
?>