<?php
{

		       if(arg(2)==null)
			 {
			   

			 }
		       else
			 {
			    $confirm = variable_get('perfanal_rankcon', '');
			    $cntry = variable_get('perfanal_cntry', '');

			    drupal_set_title(((arg(2)!='ALL')?arg(2):((arg(3)!=null & $cntry !=null)?$cntry:'')).' Meets '.arg(1).'-'.(arg(1)+1));
			    setseasons_breadcrumb(array(l('Meets','meets/'.arg(1))));
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
	
			    $query = "select SQL_CACHE DISTINCT IF(e.Meet Is Null,1,0) as results, m.Meet, m.MName, m.Start, m.End,m.type,m.Sanction, m.Course, m.Location  from ".$tm4db."meet_".$season." m inner join ".$tm4db."meet_sanctions_".$season." as sm on (m.Meet=sm.meet) left join ".$tm4db."mtevent_".$season." e on (m.Meet=e.Meet) where sm.c=1".((arg(2)!='ALL')?" and sm.abbr='".arg(2)."'":"")." ".((arg(3)!=null)?"and m.type='".arg(3)."' ":"")." ".tablesort_sql($headers);
			   
			    $result = db_query($query);
			     while ($object = db_fetch_object($result))
			      {
				 $link = (($object->results==0)?'meets/'.arg(1).'/events/'.$object->Meet:'meets/'.arg(1).'/meets_info/'.$object->Meet);

				 $class = ($object->results==0)?' green':' red';

				 $rows[] = array('class'=>$class,'data' => array(l(t($object->MName.(($object->results==0)?' Results':' info')), $link),$object->type,get_sanc($object->Sanction),get_date($object->Start), get_date($object->End), $object->Course, $object->Location));
			      }
			     $output.= drupal_get_form('perfanal_meets_filters_form');
			    $output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
			 }
		    }
?>