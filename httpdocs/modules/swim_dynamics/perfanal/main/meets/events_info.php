<?php
 $query = "select SQL_CACHE  distinct m.QTSS,m.QTS,m.QTLS,m.QTL, m.MName FROM ".$tm4db."meet_".$season." as m  where m.Meet=%d";
		       $result = db_query($query,arg(3));
		       $object = db_fetch_object($result);
		       drupal_set_title($object->MName.' Meet info'.' '.arg(1).'-'.(arg(1)+1));
		       setseasons_breadcrumb(array(l('Meets','meets/'.arg(1))));

		       $output.="<div class='tabs'><ul class='tabs primary'>";
		       $output.="<li>".l('Info','meets/'.arg(1).'/meets_info/'.arg(3))."</li>";
		       $output.=" <li class='active'>".l('Events','meets/'.arg(1).'/events_info/'.arg(3))."</li>";
		       $output.="</ul></div>";

		       $headers[] = array('data' => t('Sess'), 'width' => '20px');
		       $headers[] = array('data' => t('Event'), 'width' => '40px');
		       $headers[] = array('data' => t('Gender'), 'width' => '60px');
		       $headers[] = array('data' => t('Age'), 'width' => '100px');
		       $headers[] = array('data' => t('Distance'), 'width' => '50px');
		       $headers[] = array('data' => t('Stroke'), 'width' => '80px');
		       $headers[] = array('data' => t('I/R'), 'width' => '50px');
		       $headers[] = array('data' => t('Fee'), 'width' => '60px');
		       $headers[] = array('data' => t('Course'), 'width' => '40px');
		       $headers[] = array('data' => t('Div'), 'width' => '40px');
		       if(($QTL=$object->QTL)>0)
			 $headers[] = array('data' => t('LC Slower'), 'width' => '90px');
		       if(($QTLS=$object->QTLS)>0)
			 $headers[] = array('data' => t('LC Faster'), 'width' => '90px');
		       if(($QTS=$object->QTS)>0)
			 $headers[] = array('data' => t('SC Slower'), 'width' => '90px');
		       if(($QTSS=$object->QTSS)>0)
			 $headers[] = array('data' => t('SC Faster'), 'width' => '90px');

			 $query = "Select FastCut,SlowCut,Fast_L,Slow_L, Round(Fee,2) as Fee,Session, MtEvent, IF(Count(MtEvX)=1,Lo_Hi,'*') as Lo_Hi ,MtEv, IF(Count(Distinct Sex)=1,Sex,'X') As Sex, Distance, Stroke, I_R,If(Count(*)>0,Count(*),1) as Events, Course,Division FROM ".$tm4db."mtevente_".$season." as e WHERE e.MtEv>0 and  Meet=%d ".(arg(4)!=null?(' and e.Lo_Hi='.arg(4)):'')." ".(arg(5)!=null?(" and (e.Sex='X'or e.Sex='".arg(5)."')"):'')." ".(arg(4)!=null?(' Group by MtEv,MtEvX order by Session, MtEv,MtEvX'):' Group by MtEv order by Session, MtEv')." ";
		       $result = db_query($query,arg(3));
		       while ($object = db_fetch_object($result))
			 {
			    $link = 'meets/'.arg(1).'/event_info/'.arg(3).'/'.$object->MtEv;
			    $temp[] = $object->Session;

			    $temp[] = ($object->Events == 1)?$object->MtEv:l($object->MtEv, $link);
			    $temp[] =Gender($object->Sex);
			    $temp[] = Age($object->Lo_Hi);
			    $temp[] =  $object->Distance."m";
			    $temp[] = Stroke($object->Stroke);
			    $temp[] =  IR($object->I_R);
			    $temp[] = ($object->Events==1)?$object->Fee:'';
			    $temp[] = Course(0,$object->Course);
			    $temp[] = $object->Division;
			    if($object->Events == 1)
			      {
				 if($QTL>0)
				 
				   $temp[]=(get_time($object->Fast_L));
				 if($QTLS>0)
				   $temp[]=(get_time($object->Slow_L));
				 if($QTS>0)
				   $temp[]=(get_time($object->FastCut));
				 if($QTSS>0)
				   $temp[]=(get_time($object->SlowCut));
			      }
			    else
			      {
				 if($QTL>0)
				   $temp[]='';
				 if($QTLS>0)
				   $temp[]='';
				 if($QTS>0)
				   $temp[]='';
				 if($QTSS>0)
				   $temp[]='';
			      }
			    $rows[] = $temp;
			    $temp=null;
			 }
			 $output.= drupal_get_form('perfanal_events_filters_form');
		       $output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
		    
?>