<?php

	require('ranking_config.php');
	require('rankings_config_sanc.php');
	require('rankings_age_group_where.php');
	require('rankings_title.php');

		    if($_GET['c']!='L')
		    $age_groups[] = Array(25,1,1,1,1,null);
		    $age_groups[] = Array(50,1,1,1,1,null);
		    $age_groups[] = Array(100,1,1,1,1,($_GET['c']!='L')?1:null);
		    $age_groups[] = Array(200,1,1,1,1,1);
		    $age_groups[] = Array(400,1,null,null,null,1);
		    $age_groups[] = Array(800,1,null,null,null,null);
		    $age_groups[] = Array(1500,1,null,null,null,null);
		    
		     setseasons_breadcrumb($breadcumb);

		    drupal_set_title($pref_head.' '.$type[$_GET['type']].' '.$fina_year.' Rankings - Stroke & Distance'."<br/><small>".$heading.' '.Gender($_GET['gen']).' '.Age(($_GET['lo']*100)+$_GET['hi']).' '.Course(1,$_GET['c']).'</small> '.(($rankings_pedicative==false)?'':"<small><br>Athletes ages on ".$_GET['d'].'</small>'));
		 
		    $headers[] = array('data' => t('Distance'), 'style'=>'width:4em;');
		    $headers[] = array('data' => l2(Stroke(1),$curr_param.'&str=1&dis=All','rankings_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(2),$curr_param.'&str=2&dis=All','rankings_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(3),$curr_param.'&str=3&dis=All','rankings_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(4),$curr_param.'&str=4&dis=All','rankings_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(5),$curr_param.'&str=5&dis=All','rankings_fina.php'), 'style' => 'width:4em;');

		    foreach($age_groups as $gp)
		      {
			 $rows[] = array((($gp[0]!=null)?l2($gp[0].'m',$curr_param.'&str=All&dis='.$gp[0],'rankings_fina.php'):'-'),
					 (($gp[1]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[2]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[3]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[4]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[5]==1)?theme_image('../../images/tick.gif','*'):'-'));
		      }

		    $output.=l2('Overall Fina Points',$curr_param.'&str=All&dis=All','rankings_fina.php').' - Regardless of Stroke or Distance';
		    $output.='<br/><br/>';
		    $output.='Compare Performance within a certain stroke or distance (Table of options below).<br><ul><li>By clicking freestyle(stroke) you will be comparing only the freestyle events, by looking down the column for freestyle(stroke) you will see all the distances that are marked with a tick that are included in that comparision.</li>';
		    $output.='<br><li>Similarly you can select a specsific distance to compare performance and all the different strokes with ticks for that distance performance will be compared.</li></ul>';
		  
		    $output.= theme_table($headers, $rows,null,null);
		    render_page();
?>