<?php
 $age_groups[] = Array(50,1,1,1,1,null);
		    $age_groups[] = Array(100,1,1,1,1,1);
		    $age_groups[] = Array(200,1,1,1,1,1);
		    $age_groups[] = Array(400,1,null,null,null,1);
		    $age_groups[] = Array(800,1);
		    $age_groups[] = Array(1500,1);

		    drupal_set_title($pref_head.' '.$type[arg(2)].' '.$fina_year.' Rankings'." as of ".$last_ranking."<br/><small>".$heading.' '.Gender(arg(6)).' '.Age(arg(7)).' '.Course(1,arg(5)).'</small>');
		    $headers[] = array('data' => t('Distance'), 'width' => '70px');
		    $headers[] = array('data' => l(Stroke(1),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/1/All//2'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(2),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/2/All//2'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(3),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/3/All//2'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(4),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/4/All//2'), 'width' => '60px');
		    $headers[] = array('data' => l(Stroke(5),'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/5/All//2'), 'width' => '60px');

		    foreach($age_groups as $gp)
		      {
			 $rows[] = array((($gp[0]!=null)?l($gp[0].'m','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/All/'.$gp[0].'//2'):'-'),
					 (($gp[1]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[2]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[3]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[4]==1)?theme_image(path().'/images/tick.gif','*'):'-'),
					 (($gp[5]==1)?theme_image(path().'/images/tick.gif','*'):'-'));
		      }

		    $output.=l('Overall Fina Points','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/All/All//2').' - Regardless of Stroke or Distance';
		    $output.='<br/><br/>';

		    $output.= theme('table', $headers, $rows);
?>