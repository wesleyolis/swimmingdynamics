<?php
	 
require('../../main_include.php'); 
$display_link=0;		
	$query = "select SQL_CACHE m.* from ".$db_name."meet as m WHERE m.Meet=".inj_int($_GET['m'])." ";
	$result = db_query($query);
	if(!mysql_error())
	$object = mysql_fetch_object($result);


	drupal_set_title($object->MName.' Meet Fina Points Ranking Results'.' '.$_GET['ss'].'-'.($_GET['ss']+1).' - Stroke & Distance');
 
		$breadcumb[] = l2('Meets',null,'index.php');
		$breadcumb[] = l2('Age & Gender','m='.$_GET['m'],'meets_fina_age.php');
		setseasons_breadcrumb($breadcumb);
	
			$menu_option = 'fina';
			require('meets_menu.php');
			
		if(strpos($object->Course,'S')>0)
		$age_groups[] = Array(25,1,1,1,1,null);
		$age_groups[] = Array(50,1,1,1,1,null);
		$age_groups[] = Array(100,1,1,1,1,1);
		$age_groups[] = Array(200,1,1,1,1,1);
		$age_groups[] = Array(400,1,null,null,null,1);
		$age_groups[] = Array(800,1,null,null,null,null);
		$age_groups[] = Array(1500,1,null,null,null,null);

		   $curr_param = '';
			foreach($_GET as $key => $val)
			{
				if($key != 'ss' && $key != 'db')
				$curr_param.= (($curr_param=='')?'':'&').$key.'='.$val;
			}
	
		    $headers[] = array('data' => t('Distance'), 'style'=>'width:4em;');
		    $headers[] = array('data' => l2(Stroke(1),$curr_param.'&str=1&dis=All','meets_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(2),$curr_param.'&str=2&dis=All','meets_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(3),$curr_param.'&str=3&dis=All','meets_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(4),$curr_param.'&str=4&dis=All','meets_fina.php'), 'style' => 'width:4em;');
		    $headers[] = array('data' => l2(Stroke(5),$curr_param.'&str=5&dis=All','meets_fina.php'), 'style' => 'width:4em;');

		    foreach($age_groups as $gp)
		      {
			 $rows[] = array((($gp[0]!=null)?l2($gp[0].'m',$curr_param.'&str=All&dis='.$gp[0],'meets_fina.php'):'-'),
					 (($gp[1]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[2]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[3]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[4]==1)?theme_image('../../images/tick.gif','*'):'-'),
					 (($gp[5]==1)?theme_image('../../images/tick.gif','*'):'-'));
		      }

		    $output.='<br>'.l2('Overall Fina Points',$curr_param.'&str=All&dis=All','meets_fina.php').' - Regardless of Stroke or Distance (This option is better for meets.)';
		    $output.='<br><br>';
		      $output.='Compare Performance within a certain stroke or distance (Table of options below).<br><ul><li>By clicking freestyle(stroke) you will be comparing only the freestyle events, by looking down the column for freestyle(stroke) you will see all the distances that are marked with a tick that are included in that comparision.</li>';
		    $output.='<br><li>Similarly you can select a specsific distance to compare performance and all the different strokes with ticks for that distance performance will be compared.</li></ul>';
		  
		    $output.= theme_table($headers, $rows,null,null);
		    
		    render_page();

?>