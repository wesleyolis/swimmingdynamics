<?php
 $age_groups[] = Array(50,1,1,1,1,null);
		    $age_groups[] = Array(100,1,1,1,1,1);
		    $age_groups[] = Array(200,1,1,1,1,1);
		    $age_groups[] = Array(400,1,null,null,null,1);
		    $age_groups[] = Array(800,1,null,null,null,null);
		    $age_groups[] = Array(1500,1,null,null,null,null);

		    $rows=array();
		    
		    $headers[] = array('data' => t('Distance'), 'width' => '70px');
		 
		    $headers[] = array('data' => l2(Stroke(1),'ath='.$_GET['ath'].'&st=1&dis=All','fina.php'), 'width' => '60px');
		    $headers[] = array('data' => l2(Stroke(2),'ath='.$_GET['ath'].'&st=2&dis=All','fina.php'), 'width' => '60px');
		    $headers[] = array('data' => l2(Stroke(3),'ath='.$_GET['ath'].'&st=3&dis=All','fina.php'), 'width' => '60px');
		    $headers[] = array('data' => l2(Stroke(4),'ath='.$_GET['ath'].'&st=4&dis=All','fina.php'), 'width' => '60px');
		    $headers[] = array('data' => l2(Stroke(5),'ath='.$_GET['ath'].'&st=5&dis=All','fina.php'), 'width' => '60px');

		    foreach($age_groups as $gp)
		      {
			 $rows[] = array((($gp[0]!=null)?l2($gp[0].'m','ath='.$_GET['ath'].'&st=All&dis='.$gp[0],'fina.php'):'-'),
					 (($gp[1]==1)?theme_image('../../../images/tick.gif','*'):'-'),
					 (($gp[2]==1)?theme_image('../../../images/tick.gif','*'):'-'),
					 (($gp[3]==1)?theme_image('../../../images/tick.gif','*'):'-'),
					 (($gp[4]==1)?theme_image('../../../images/tick.gif','*'):'-'),
					 (($gp[5]==1)?theme_image('../../../images/tick.gif','*'):'-'));
		      }

		    $output.=l2('Overall Perfomance - Fina Points','ath='.$_GET['ath'].'&st=All&dis=All','fina.php').' - Regardless of Stroke or Distance';
		    $output.='<br/><br/>';
		     $output.='Compare Performance within a certain stroke or distance (Table of options below).<br><br>By clicking freestyle(stroke) you will be comparing only the freestyle events,<br> by looking down the column for freestyle(stroke) you will see all the distances<br> that are marked with a tick that are included in that comparision.';
		    $output.='<br><br>Similarly you can select a specsific distance to compare performance<br>and all the different strokes with ticks for that distance performance will be compared.';
		    $output.= theme_table($headers, $rows,null,null);
?>