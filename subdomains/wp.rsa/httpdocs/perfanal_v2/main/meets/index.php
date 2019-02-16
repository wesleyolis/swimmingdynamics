<?php
require('../../main_include.php'); 

$meta_tags.='<meta name="robots" content="index, follow">';

drupal_set_title('Meets '.$_GET['ss'].'-'.($_GET['ss']+1));
			 
			   

			    $headers[] = array('data' => t('Abbr'), 'width' => '4em');
			    $headers[] = array('nowrap'=>'nowrap','data' => t('Meets in Group'));
			    $headers[] = array('nowrap'=>'nowrap','data' => t('Next Meet'));
			    $headers[] = array('nowrap'=>'nowrap','data' => t('Last Meet Results in'));
			    
			      $query="Select SQL_CACHE  c.abbr,c._desc,e.meet as emeet,m.meet,m.MName,if(CURRENT_DATE>= m.end,1,0) as dt  From ".$db_name."code as c inner join ".$db_name."meet_sanctions as sm on (c.abbr=sm.abbr) inner join ".$db_name."meet as  m on (m.meet=sm.meet) left join ".$db_name."mtevent as e on (m.Meet=e.Meet)  where sm.c=1 and m.Start is not null and m.End is not null and c.TYPE=3 group by sm.abbr,m.meet order by c.abbr,m.end desc";
			    $results = db_query($query);
			    $abbr =null;
			    
			    
			    $rows[] = array(l2('ALL','sm=All&type=All','type.php'),' Unofficial',null,null);
			    
			    $events=null;
			    $object_prev=null;
			    $result=null;
			    if(!mysql_error())
			    while($object = mysql_fetch_object($results))   
			    {

				    //echo $object->meet.':'.$object->dt.'<br>';
				if($abbr!=$object->abbr)
				{
					//echo'hello:'.(($events==false)?0:1).':'.(($results==false)?0:1);
					if($abbr!=null)
					{
						$rows[]=array(
						l2($object_prev->abbr,'sm='.$object_prev->abbr.'&type=All','type.php'),
						$object_prev->_desc,
						$events,
						$result
						);
						$events=null;
						$result=null;
					}
						$abbr=$object->abbr;
				}
				
				
				$object_prev = $object;
				if($object->emeet==null & $object->dt==0)
					{
						$events=l2($object->MName,'m='.$object->meet.'&age=All&gen=All','events_info.php');
					}
				if($object->emeet!=null & $object->dt==1 & $result==null)
					{
						$result=l2($object->MName.' Results','m='.$object->meet.'&age=All&gen=All','events.php');
					}
			    }
			    			$rows[]=array(
						l2($object_prev->abbr,'sm='.$object_prev->abbr.'&type=All','type.php'),
						$object_prev->_desc,
						$events,
						$result
						);

			    $output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper','align'=>'left'),null);
			    $output.=$banner768_160;
			    
			    
			    render_page();
?>