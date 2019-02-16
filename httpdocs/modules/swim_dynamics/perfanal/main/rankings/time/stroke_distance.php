<?php
 if(arg(6)=='S')
		    $age_groups[] = Array(25,25,25,25,null);
		  $age_groups[] = Array(50,50,50,50,null);
		  $age_groups[] = Array(100,100,100,100,100);
		  $age_groups[] = Array(200,200,200,200,200);
		  $age_groups[] = Array(400,null,null,null,400);
		  $age_groups[] = Array(800);
		  $age_groups[] = Array(1500);

		  drupal_set_title($pref_head.' '.$type[arg(3)].' Rankings'." as of ".$last_ranking."<br/><small>".$heading.' '.Gender(arg(7)).' '.Age(arg(8)).' '.Course(1,arg(6)).'</small>'.((arg(2)=='curr')?'':"<small><br>Athletes ages on ".arg(2).'</small>'));
		  $headers[] = array('data' => t(Stroke(1)), 'width' => '80px');
		  $headers[] = array('data' => t(Stroke(2)), 'width' => '80px');
		  $headers[] = array('data' => t(Stroke(3)), 'width' => '80px');
		  $headers[] = array('data' => t(Stroke(4)), 'width' => '80px');
		  $headers[] = array('data' => t(Stroke(5)), 'width' => '80px');

		   foreach($age_groups as $row)
		    {
			    for($i=0;$i<5;$i++)
			    {
				    $col[] = (($row[$i]!=null)?l($row[$i].'m','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/'.arg(8).'/'.($i+1).'/'.$row[$i]):'-');
			    }
			    $rows[]=$col;
			    $col=null;
		    }

		  $output.= theme('table', $headers, $rows);
		  $output.='Open Water - Freestyle<br>';
		  $rows=null;
		  $gp=null;
		  $gp = Array(Array((15)=>1500,30=>3000),Array(50=>5000,100=>10000));
		  foreach($gp as $row)
		    {
			    
			    foreach($row as $key =>$value)
			    {
				    $col[] = l(($key/10).' Km','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/'.arg(8).'/1/'.$value);
			    }
			    $rows[]=$col;
			    $col=null;
		    }
		  $output.= theme('table', null, $rows);
		  
?>