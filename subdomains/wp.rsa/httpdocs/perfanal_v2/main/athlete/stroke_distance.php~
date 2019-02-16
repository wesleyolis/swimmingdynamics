<?php
 $age_groups[] = Array(50,1,1,1,1,null);
		    $age_groups[] = Array(100,1,1,1,1,1);
		    $age_groups[] = Array(200,1,1,1,1,1);
		    $age_groups[] = Array(400,1,null,null,null,1);
		    $age_groups[] = Array(800,1);
		    $age_groups[] = Array(1500,1);

		    $rows=array();
		    
		    $headers[] = array('data' => t('Distance'), 'width' => '70px');
		 
		    $headers[] = array('data' => l(Stroke(1),'athlete/'.arg(1).'/graphs_fina/'.arg(3).'/1/All'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(2),'athlete/'.arg(1).'/graphs_fina/'.arg(3).'/2/All'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(3),'athlete/'.arg(1).'/graphs_fina/'.arg(3).'/3/All'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(4),'athlete/'.arg(1).'/graphs_fina/'.arg(3).'/4/All'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(5),'athlete/'.arg(1).'/graphs_fina/'.arg(3).'/5/All'), 'width' => '60px');

		    foreach($age_groups as $gp)
		      {
			 $rows[] = array((($gp[0]!=null)?l($gp[0].'m','athlete/'.arg(1).'/graphs_fina/'.arg(3).'/All/'.$gp[0]):'-'),
					 (($gp[1]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[2]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[3]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[4]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[5]==1)?theme_image(path().'/images/tick.gif','*'):'-'));
		      }

		    $output.=l('Overall Perfomance - Fina Points','athlete/'.arg(1).'/graphs_fina/'.arg(3).'/All/All').' - Regardless of Stroke or Distance';
		    $output.='<br/><br/>';

		    $output.= theme('table', $headers, $rows);
?>