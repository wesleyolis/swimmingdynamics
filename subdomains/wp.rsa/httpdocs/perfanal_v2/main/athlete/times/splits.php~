<?php
 $output.= athlete_heading($tm4db,$season,arg(3));

		
 
	       if(arg(2)=='splits')
	       {
		       
		 $query= "Select r.Distance,r.Stroke,r.Course,m.MName,m.Start From ".$tm4db."result_".$season." as r left join ".$tm4db."meet_".$season." as m on (r.meet=m.meet) Where Result=".arg(4)."";
		 $result = db_query($query);
		   if($object = db_fetch_object($result))
		   {
			   $output.=$object->Distance.'m '.stroke($object->Stroke).' '.course(1,$object->Course).' at '.$object->MName.', '.get_date($object->Start);
		   }
		  
		  
		 
		  $query= "Select SplitIndex,Split From ".$tm4db."splits_".$season." Where SplitID=".arg(4)." order by splitindex";
		  
		  $result = db_query($query);
	
		       $graph_data ='';
		       $graph_hdrs='';
		      
		 $coloms = 8;
		 $i2=0;
		 $i=0;
		 $prev_time=0;
		 $split_num=1;
		 while($object = db_fetch_object($result))
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
		  
		   $rows[] = array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px' height='225px'><div class='cellrel'><div class='graphsback'>".theme_image(path()."/images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image(path()."/grapher.php".($graph_hdrs!=''?'?headers='.$graph_hdrs.'&data1='.$graph_data:'?headers=&data1=0|'),null,null,null,false)."</div></td></tr></table>", 'colspan' => $coloms,'align'=>'center'));
		  else
		  $rows[] = null;
		  $rows =  array_merge(	$rows,$row); 
		   $output.= '<br><div style="font-weight: bold;">';
		  $output.='</div><hr>';
		       $output.= theme('table', null, $rows).'<br>';
		   
	       } 
	       else
	       {
		       if(arg(6)!=null)
		       {
			       $where =  " r.athlete=".arg(3)." and stroke=".arg(4)." and distance=".arg(5)." and r.course='".arg(6)."'";
			        $split_distance = arg(7);
		       }
		       else
		       { 		
			       if($_POST['submit']=='100m')
			        $split_distance = 4;
				else
				if($_POST['submit']=='50m')
			        $split_distance = 2;
				else
			        $split_distance = 1;
			  
				       $where = '1=2 ';
				       $output.='<form method="POST" action="?q=athlete/'.arg(1).'/split/'.arg(3).'/'.arg(4).'/'.arg(5).'">';
				       if(count($_POST['sp'])>0)
				       foreach($_POST['sp'] as $item) {
				        $where.=' or s.SplitID='.$item;
				       }
				       $form='<span id="no_print">Splits at </span>';
				       if($split_distance !=4)
				       $form.='<input name="submit" id="no_print" type="submit" value="100m">';
				       if($split_distance !=2)
				       $form.='  <input name="submit" id="no_print" type="submit" value="50m">';
				       if($split_distance !=1)
				       $form.='  <input name="submit" id="no_print" type="submit" value="25m">';
				       
				       /*
				  for($i=0;$i<count($_POST['sp']);$i++)
				  {
					  $where.=' or s.SplitID='.$_POST['sp'][$i];
				  }*/
				  
				 
		       }
		        $query= "Select r.result,r.score,r.F_P, s.SplitIndex,s.Split,r.Distance,r.Stroke,r.Course,m.MName,m.Start From ".$tm4db."splits_".$season." as s left join ".$tm4db."result_".$season." as r on (r.result=s.SplitID) left join ".$tm4db."meet_".$season." as m on (r.meet=m.meet) Where (".$where.") order by r.score,r.F_P,r.result,s.splitindex";
				  
			
		       
				       
				       
				           // echo $query;
				       $results = db_query($query);
			
				       $output.=arg(5).'m '.stroke(arg(4)).' '.course(1,arg(6)).' at '.($split_distance*25).'m<br>';
				       
				       $colors = array('blue','#CCCC00','red','green','orange','purple','brown');
				       
			       $data=null;
					      
				 $prev_time=0;
				 $res=-1;
				 $result=null;
				 $split_count =0;
		
				//distance/25
				 $more=4;
				 $split_num=1;
				 $meets=null;
				 while($object = db_fetch_object($results))
				 {
					 $split_dis = $object->SplitIndex%$split_distance;
					 if($split_dis==0)
					 {
						 if($result!=$object->result)
						 {
							$result = $object->result;
							
							$meets[]='<input id="no_print" checked type="checkbox" name="sp['.$object->result.']" value="'.$object->result.'"> '.(get_time($object->score).' '.FP($object->F_P).' - '.$object->MName.', '.get_date($object->Start)); 
							//=array('style'=>'color:'.$colors[$res],'data'=>
							
							$res++;	 
							$prev_time=0;
							if($split_count<$object->Split)
							$split_count=$object->Split;
							
							
						 }
						 /*
						 echo $object->Split;
						 if($object->Split==null)
						 {
							echo 'true<br>'; 
						 }
						 else
						 {
							 echo 'false<br>';
						 }*/
							if($object->Split>0)
							{
								
								if($time==0)
								{
									$time = round(($object->Split -$prev_time)/$split_num);
								}
								else
								$time = ($object->Split -$prev_time);
								
								if($time<0)
								$time=0;
								
								$split_num=1;
							}
							else
							{
								$time=0;
								$split_num++;
							}
							
							$data[$object->SplitIndex][$res]=$time/*$object->Split.':'.$object->SplitIndex*/;
							if($object->Split>0)
							$prev_time=$object->Split;	
					 }
					 else
					 {
						 if($more>$split_dis)
						 $more = $split_dis;
					 }
		
						
				 }
			//	print_r($data);
				 
			
				
				       
			
				 if( $split_count!=0)
				 {
				  $graph_data=null;;
				  $graph_hdrs='';
				      
				 $coloms = 8;
				 $i2=0;
				 $i=0;
						
				 for($data_i=1;$data_i<$split_count;$data_i++)
				 {
					
					 
					if($data[$data_i]!=null)
					{
						$dis=$data_i;
						$times=$data[$data_i];
				
					
						 if($i2>=$coloms)
						 {
							 $i+=1;
							 $i2=1;
							 $rw=0;
							foreach($cols as $col=>$val)
							{
								$row[] = array('style'=>($rw>0)?'color:'.($colors[$rw-1]):'','data'=>$val); 
								$rw++;
							}
							//print_r($row);
							//echo $graph_hdrs;
							$cols = null;
						 }
						 else
							$i2+=1;
							
						$cols[0][] = '<b>'.($dis*25).'m</b>';//array('width'=>'50','data'=>$dis);
						$graph_hdrs.= ($dis*25).'m|';
						 
						 for($col_i = 0;$col_i<= $res;$col_i++)
						//foreach($times as $key=>$time)
						{
							if($times[$col_i]!=null)
							{
								$cols[$col_i+1][] = '<b>'.get_time($times[$col_i]).'</b>';
								$graph_data[$col_i] .= $times[$col_i].'|';
							}
							else
							{
								$cols[$col_i+1][] ='';
								$graph_data[$col_i] .= '-|';
							}
			
							
							
						}
					}
					
				}
				$rw=0;
				foreach($cols as $col=>$val)
				{
					$row[] = array('style'=>($rw>0)?'color:'.($colors[$rw-1]):'','data'=>$val); 
					$rw++;
				}
				
				
						
				//$output.= theme('table', null, $row).'<br>';
				$graph_d='';
				for($i=0;$i<=$res;$i++)
				{
					$graph_d .= '&data'.($i+1).'='.$graph_data[$i];
				}
					// &data='.$graph_data
			
					
					
				  if($graph_hdrs!='')
				  {
				  
				   $rows[] = array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px' height='225px'><div class='cellrel'><div class='graphsback'>".theme_image(path()."/images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image(path()."/grapher.php".($graph_hdrs!=''?'?headers='.$graph_hdrs.$graph_d.'&data='.$graph_data:'?headers=&data=0|'),null,null,null,false)."</div></td></tr></table>", 'colspan' => $coloms,'align'=>'center'));
				  $rows[] = array(array('data' =>$form,'colspan'=>$coloms));
				  
				  $rw=0;
				  $mout='';
				foreach($meets as $meet=>$val)
					{
						$mout.='<span style="color:'.$colors[$rw].'">'.$val.'</span><br>';
						$rw++;
					}
					 $mout.='<input name="submit" id="no_print" type="submit" value="Split Comparison">';
					 $rows[] = array(array('data' =>$mout,'colspan'=>$coloms));
				  }
				   else
				  $rows[] = null;
				  $rows =  array_merge(	$rows,$row); 
				   $output.= '<br><div style="font-weight: bold;">';
				  $output.='</div><hr>';
				  $output.= theme('table', null, $rows);
				
				   }
				  
			       else
			       {
				       $output.='No Splits Selected';
			       }
			       $output.='</form>';
		       
	       }
	      
		
		 
	       
	       
?>