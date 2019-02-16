<?php


		    
			    $confirm = variable_get('perfanal_rankcon', '');
			    $cntry = variable_get('perfanal_cntry', '');
			    $rank_days_before = variable_get('perfanal_ranking_days_before','2');

			    drupal_set_title(((arg(2)!='ALL')?arg(3):((arg(4)!=null & $cntry !=null)?$cntry:'')).' Meets');
			    setseasons_breadcrumb(array(l('Meets Types','meets/'.arg(1))));
			    //$output.= highlight_js('meets');
			    //$output.= drupal_get_form('perfanal_meet_filters_form');

			    $headers[] = array('data' => t('Meet'), 'width' => '300px','field' => 'm.MName');
			    $headers[] = array('data' => t('Type'), 'width' => '20px');
			    $headers[] = array('data' => t('Sanc'), 'width' => '20px');
			    $headers[] = array('data' => t('Start date'), 'width' => '130px','sort' => 'desc','field' => 'm.Start');
			    $headers[] = array('data' => t('End date'), 'width' => '100px');
			    $headers[] = array('data' => t('Course'), 'width' => '40px');
			    $headers[] = array('data' => t('Location'));
			    // order by m.Start DESC

			       $rank_from_option = variable_get('perfanal_rank_from','y');
	     
			       $Sd = variable_get('perfanal_ranking_dd', '01');
			       $Sm = variable_get('perfanal_ranking_mm', '01');
			       $from_date = $season.'-'.$Sm.'-'.$Sd;
	
			    $query = "select SQL_CACHE DISTINCT IF(e.Meet Is Null,1,0) as results, m.Meet, m.MName, m.Start, m.End,m.type,m.Sanction, m.Course, m.Location  from ".$tm4db."meet_".$season." m left join ".$tm4db."meet_sanctions_".$season." as sm on (m.Meet=sm.meet) left join ".$tm4db."mtevent_".$season." e on (m.Meet=e.Meet) where m.Start is not null and m.End is not null  ".((arg(3)=='ALL')?''/*(($confirm=='')?' ':(" and (isnull(sm.abbr) or INSTR(sm.abbr,'".$cntry.",')=0 ) "))*/:" and sm.abbr='".arg(3)."' ".(($confirm!='' & arg(4)!=null)?' and sm.include=1':'')." ").((arg(5)=='ALL' || arg(5)==null)?'':" and instr(m.Course,'".arg(5)."')>0").' '.((arg(4)==null)?'':(" and (DATEDIFF(m.End,'".arg(4)."')<=-".$rank_days_before." )".(($rank_from_option=='y')?"and (DATEDIFF(m.End,'".$from_date."')>=0 )":'')))." ".((arg(4)!=null)?' and e.Meet Is not Null':'')." ".tablesort_sql($headers,(((arg(4)==null || arg(3)=='ALL') && arg(3)!='ALL')?'sm.c asc,':''));
			   
			    $result = db_query($query);
			     while ($object = db_fetch_object($result))
			      {
				 $link = (($object->results==0)?'meets/'.arg(1).'/events/'.$object->Meet:'meets/'.arg(1).'/info/'.$object->Meet);

				 $class = ($object->results==0)?' green':' red';

				 $rows[] = array('class'=>$class,'data' => array(l(t($object->MName), $link),$object->type,get_sanc($object->Sanction),get_date($object->Start), get_date($object->End), $object->Course, $object->Location));
			      }
			    $output= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
			 
		   
?>