<?php
	 
	
 	require('../../../main_include.php');
	require('../heading.php');
	header("Cache-Control: max-age=300, none");
	
	drupal_set_title('Result Split - '.$heading);
		       
		 $query= "Select r.Distance,r.Stroke,r.Course,m.MName,m.Start From ".$db_name."result as r left join ".$db_name."meet as m on (r.meet=m.meet) Where Result=".inj_int($_GET['sp']);
		 $result = db_query($query);
		 if(!mysql_error())
		   if($object = mysql_fetch_object($result))
		   {
			   $output.=$object->Distance.'m '.stroke($object->Stroke).' '.course(1,$object->Course).' at '.$object->MName.', '.get_date($object->Start);
		   }
		  
		  
		 
		  $query= "Select SplitIndex,Split From ".$db_name."splits Where SplitID=".inj_int($_GET['sp'])." order by splitindex";
		  
		  $result = db_query($query);
	
		       $graph_data ='';
		       $graph_hdrs='';
		      
		 $coloms = 8;
		 $i2=0;
		 $i=0;
		 $prev_time=0;
		 $split_num=1;
		 $time=null;
		 if(!mysql_error())
		 while($object = mysql_fetch_object($result))
		 {
			 if($i2>=$coloms)
			 {
				 $i+=1;
				 $i2=1;
				 $row[] = $col_h;
				 $row[] = $col_d2;
				$row[] = $col_d1;
				
				$dis = ($object->SplitIndex*25).'m';
				$col_h=null;
				$col_d2=null;
				$col_d1=null;
			 }
			 else
			 	$i2+=1;
				
			$dis = ($object->SplitIndex*25).'m';
			$col_h[] = '<b>'.$dis.'</b>';//array('width'=>'50','data'=>$dis);
			
			if($object->Split>0)
			{
				
				if($time==0)
				{
					$time = round(($object->Split -$prev_time)/$split_num);
				}
				else
				$time = ($object->Split -$prev_time);
				
				if($time<0)
				$time='-';
				
				$split_num=1;
				
				 $graph_hdrs.= $dis.'|';
				 $graph_data.= $time.'|';
				 
				 $prev_time=$object->Split;
			}
			else
			{
				$time=0;
				$split_num++;
			}
			$col_d2[] = '<b>'.get_time($time).'</b>';
			$col_d1[] = get_time($object->Split);
			//$time = $object->Split -$prev_time;
			
			
			
			
			
			
			   
				
			
		
		}
		 $row[] = $col_h;
		 $row[] = $col_d2;
		$row[] = $col_d1;
		
 
			 
	 
		  if($graph_hdrs!='')
		  
		   $rows[] = array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px' height='225px'><div class='cellrel'><div class='graphsback'>".theme_image("../../../images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image("../../../grapher.php".($graph_hdrs!=''?'?headers='.$graph_hdrs.'&data1='.$graph_data:'?headers=&data1=0|'),null,null,null,false)."</div></td></tr></table>", 'colspan' => $coloms,'align'=>'center'));
		  else
		  $rows[] = null;
		  $rows =  array_merge(	$rows,$row); 
		   $output.= '<br><div style="font-weight: bold;">';
		  $output.='</div><hr>';
		       $output.= theme_table(null, $rows,null,null).'<br>';
	
	      
		
		 
	       render_page();
	       
?>