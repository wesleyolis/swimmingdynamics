<?php
	require('ranking_config.php');
	require('rankings_config_sanc.php');
	require('rankings_age_group_where.php');
	require('rankings_title.php');

		if($_GET['c']=='S')
		  $age_groups[] = Array(25,25,25,25,null);
		  $age_groups[] = Array(50,50,50,50,null);
		  $age_groups[] = Array(100,100,100,100,($_GET['c']=='L')?null:100);
		  $age_groups[] = Array(200,200,200,200,200);
		  $age_groups[] = Array(400,null,null,null,400);
		  $age_groups[] = Array(800,null,null,null,null);
		  $age_groups[] = Array(1500,null,null,null,null);

		  setseasons_breadcrumb($breadcumb);
		  
		 
		  drupal_set_title($pref_head.' '.$type[$_GET['type']].' Rankings - Stroke & Distance<br><small>'.$heading.' '.Gender($_GET['gen']).' '.Age(($_GET['lo']*100)+$_GET['hi']).' '.Course(1,$_GET['c']).'</small> '.(($rankings_predicative==false)?'':"<small><br>Athletes ages on ".$_GET['d'].'</small>'));

		  $headers[] = array('data' => t(Stroke(1)), 'style' => 'width:4em;');
		  $headers[] = array('data' => t(Stroke(2)), 'style' => 'width:4em;');
		  $headers[] = array('data' => t(Stroke(3)), 'style' => 'width:4em;');
		  $headers[] = array('data' => t(Stroke(4)), 'style' => 'width:4em;');
		  $headers[] = array('data' => t(Stroke(5)), 'style' => 'width:4em;');

		   foreach($age_groups as $row)
		    {
			    for($i=0;$i<5;$i++)
			    {
				    $col[] = (($row[$i]!=null)?l2($row[$i].'m',$curr_param.'&str='.($i+1).'&dis='.$row[$i],'rankings_time.php'):'-');
			    }
			    $rows[]=$col;
			    $col=null;
		    }

		  $output.= theme_table($headers, $rows,null,null);
		  $output.='Open Water - Freestyle<br>';
		  $rows=null;
		  $gp=null;
		  $gp = Array(Array(1=>1000,3=>3000),Array(5=>5000,10=>10000));
		  foreach($gp as $row)
		    {
			    
			    foreach($row as $key =>$value)
			    {
				    $col[] = l2($key.' Km',$curr_param.'&str=1&dis='.$value,'rankings_time.php');
			    }
			    $rows[]=$col;
			    $col=null;
		    }
		  $output.= theme_table(null, $rows,null,null);
		  
		  render_page();
		  
?>