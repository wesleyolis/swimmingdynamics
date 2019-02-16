<?php
$option = 'graphs';
require('../../../main_include.php');
require('../heading.php');


drupal_set_title('Performance Graphs Menu - '.$heading);

		  $header[] = array('data' => t('Distance - Time'), 'width' => '110px');
		  //$header[] = array('data' => t('Stroke'), 'width' => '80px');

		  $result = db_query("select r.DISTANCE, r.STROKE from ".$db_name."result r where r.ATHLETE=".inj_int($_GET['ath'])." and r.I_R <> 'R' and r.NT =0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE");
		  $output.='Please select an option.<br>';
		  $rows[] = array(l2('ALL','ath='.$_GET['ath'].'&st=ALL&dis=ALL','times.php'));
		  if(!mysql_error())
		  while ($object = mysql_fetch_object($result))
		    {
		       $rows[] = array(l2($object->DISTANCE.' '.stroke($object->STROKE),'ath='.$_GET['ath'].'&st='.$object->STROKE.'&dis='.$object->DISTANCE,'times.php'));
		    }
		  $output.="<div style='padding:20px;' width='200px' align='left'>";
		    $output.= theme_table($header, $rows,array('id'=>'hyper','class'=>'hyper','align'=>'left'),null).'<br></div>';
		  $output.="<div align='center'>";
		  require('stroke_distance.php');
		  $output.="</div>";
		  
		  render_page();

?>