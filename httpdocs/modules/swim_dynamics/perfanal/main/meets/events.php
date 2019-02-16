<?php
 $query = "select SQL_CACHE  distinct m.QTSS,m.QTS,m.QTLS,m.QTL,IF(e.Meet Is Null,1,0) as results, m.MName FROM ".$tm4db."meet_".$season." as m left join ".$tm4db."mtevent_".$season." e on (m.Meet=e.Meet) where m.Meet=%d";
		     
		       $result = db_query($query,arg(3));
		       $object = db_fetch_object($result);
		       $display_link = $object->results;
		       drupal_set_title($object->MName.' Meet '.($display_link==0?'Results':'Info').' '.arg(1).'-'.(arg(1)+1));
		       setseasons_breadcrumb(array(l('Meets','meets/'.arg(1))));
		       $output.="<div class='tabs'><ul class='tabs primary'>";
		       $output.="<li>".l('Info','meets/'.arg(1).'/meets_info/'.arg(3))."</li>";
		       $output.=" <li class='active'>".l('Events','meets/'.arg(1).'/events/'.arg(3))."</li>";
		       If($display_link==0)
			 {
			    $output.="<li>".l('Individual Points','meets/'.arg(1).'/points/'.arg(3))."</li>";
			    $output.="<li>".l('Team Points','meets/'.arg(1).'/team_points/'.arg(3))."</li>";
			    $output.="<li>".l('Fina Points','meets/'.arg(1).'/fina_points/'.arg(3))."</li>";
			 }
		       $output.="</ul></div>";

		       $headers[] = array('data' => t('Event'), 'width' => '50px');
		       $headers[] = array('data' => t('Gender'), 'width' => '60px');
		       $headers[] = array('data' => t('Age'), 'width' => '100px');
		       $headers[] = array('data' => t('Distance'), 'width' => '50px');
		       $headers[] = array('data' => t('Stroke'), 'width' => '80px');
		       $headers[] = array('data' => t('I/R'), 'width' => '40px');
		       $headers[] = array('data' => t('Course'), 'width' => '40px');
		       $headers[] = array('data' => t('Div'), 'width' => '40px');

		       $query = "Select DISTINCT MtEvent, IF(Count(MtEvX)=1,Lo_Hi,'*') as Lo_Hi ,MtEv, IF(Count(Distinct Sex)=1,Sex,'X') As Sex, Distance, Stroke, I_R,If(Count(*)>0,Count(*),1) as Events, Course,Division, Sum(Results) as Results FROM ".$tm4db."mtevent_".$season." as e WHERE Meet=%d ".(arg(4)!=null?' and e.Lo_Hi='.arg(4):'')." ".(arg(5)!=null?(" and (e.Sex='X'or e.Sex='".arg(5)."')"):'')." Group by MtEv order by MtEv";
		       $result = db_query($query,arg(3));

		       while ($object = db_fetch_object($result))
			 {

			    $link = 'meets/'.arg(1).'/'.(($object->Events==1)?'event/'.(($object->I_R =='I')?'results/':'result/').$object->MtEvent:((($object->Results)<$min_display)?'events/'.(($object->I_R =='I')?'results/':'result/').arg(3).'/'.$object->MtEv:'event/'.arg(3).'/'.$object->MtEv));
			    $rows[] = array((($display_link==0)?l(t($object->MtEv.(($object->Events>1 && $object->Results<$min_display)?'*':'')), $link):$object->MtEv),Gender($object->Sex), Age($object->Lo_Hi), $object->Distance."m", Stroke($object->Stroke), IR($object->I_R), Course(0,$object->Course),$object->Division);
			 }
			$output.= drupal_get_form('perfanal_events_filters_form');
		       $output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
?>