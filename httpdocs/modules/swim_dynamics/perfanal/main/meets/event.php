<?php
$query = "select m.MName,e.Lo_Hi, e.Distance, e.Stroke, e.Course, e.I_R from ".$tm4db."mtevent_".$season." e, ".$tm4db."meet_".$season." m where m.Meet=%d And e.MtEv=%d and e.Meet=m.Meet";
$result = db_query($query,arg(3),arg(4));
$object = db_fetch_object($result);

drupal_set_title($object->MName.' Meet Results '.arg(1).'-'.(arg(1)+1));
setseasons_breadcrumb(array(l('Meets','meets/'.arg(1)),l('Events','meets/'.arg(1).'/events/'.arg(3))));
//.' Meet'."&nbsp;<br><p><b><small>Event: ".arg(4).' &nbsp;&nbsp;'.Age($object->Lo_Hi).' &nbsp;'.$object->Distance.'m &nbsp;'.Stroke($object->Stroke).' &nbsp;&nbsp;'.IR($object->I_R).' &nbsp;'.Course(1,$object->Course).'</small></b></p>');

$output .= "<br><p class='title' align=\'center\'>"."<small>Event: ".arg(4).' &nbsp;&nbsp;'.Age($object->Lo_Hi).' &nbsp;'.$object->Distance.'m &nbsp;'.Stroke($object->Stroke).' &nbsp;&nbsp;'.IR($object->I_R).' &nbsp;'.Course(1,$object->Course)."</small></p><br>";

$output.='<br>'.l('All Events Results','meets/'.arg(1).'/'.(($object->I_R =='I')?'events/results/':'events/result/').arg(3).'/'.arg(4)).'<br><br>';
$headers[] = array('data' => t('Event'), 'width' => '50px');
$headers[] = array('data' => t('Gender'), 'width' => '60px');
$headers[] = array('data' => t('Age'), 'width' => '100px');
$headers[] = array('data' => t('Div'), 'width' => '40px');

$result = db_query("Select MtEvent, MtEvX, Sex, Lo_Hi, I_R,Division FROM ".$tm4db."mtevent_".$season." WHERE Meet=%d And MtEv=%d order by MtEvX",arg(3),arg(4));

while ($object = db_fetch_object($result))
 {
    $link = 'meets/'.arg(1).'/'.(($object->I_R =='I')?'event/results/':'event/result/').$object->MtEvent;
    $rows[] = array(l(t($object->MtEvX), $link),Gender($object->Sex), Age($object->Lo_Hi),$object->Division);

 }
$output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
?>