<?php

require('../../main_include.php'); 


$query = "select SQL_CACHE distinct m.QTSS,m.QTS,m.QTLS,m.QTL,m.QTYS,m.QTY, m.MName,e.Lo_Hi, e.Distance, e.Stroke,e.Sex, e.Course, e.I_R from ".$db_name."mtevente as e inner join ".$db_name."meet as m on (e.Meet=m.Meet) where m.Meet=".inj_int($_GET['m'])." And e.MtEv=".inj_int($_GET['ev'])." ";
		       

			$result = db_query($query);
			if(!mysql_error())
		       $object = mysql_fetch_object($result);

		       drupal_set_title($object->MName.' Meet info '.$_GET['ss'].'-'.($_GET['ss']+1));
		       setseasons_breadcrumb(array(l2('Meets',null,'index.php'),l2('Events','&m='.$_GET['m'].'&age=All&gen=All','events_info.php')));
		       //.' Meet'."&nbsp;<br><p><b><small>Event: ".arg(4).' &nbsp;&nbsp;'.Age($object->Lo_Hi).' &nbsp;'.$object->Distance.'m &nbsp;'.Stroke($object->Stroke).' &nbsp;&nbsp;'.IR($object->I_R).' &nbsp;'.Course(1,$object->Course).'</small></b></p>');

		       $output .= "<br><p class='title' align=\'center\'>"."<small>Event: ".$_GET['ev'].' &nbsp;&nbsp;'.Gender($object->Sex).' &nbsp;'.Age($object->Lo_Hi).' &nbsp;'.$object->Distance.'m &nbsp;'.Stroke($object->Stroke).' &nbsp;&nbsp;'.IR($object->I_R).' &nbsp;'.Course(1,$object->Course)."</small></p>";

		       $headers[] = array('data' => t('Event'), 'width' => '50px');
		       $headers[] = array('data' => t('Gender'), 'width' => '30px');
		       $headers[] = array('data' => t('Age'), 'width' => '100px');
		       $headers[] = array('data' => t('Fee'), 'width' => '60px');
		        $headers[] = array('data' => t('Div'), 'width' => '40px');
		       if(($QTL=$object->QTL)>0)
			 $headers[] = array('data' => t('LC Slower'), 'width' => '90px');
		       if(($QTLS=$object->QTLS)>0)
			 $headers[] = array('data' => t('LC Faster'), 'width' => '90px');
		       if(($QTS=$object->QTS)>0)
			 $headers[] = array('data' => t('SC Slower'), 'width' => '90px');
		       if(($QTSS=$object->QTSS)>0)
			 $headers[] = array('data' => t('SC Faster'), 'width' => '90px');
		       if(($QTY=$object->QTY)>0)
			 $headers[] = array('data' => t('Yard Slower'));
		       if(($QTYS=$object->QTYS)>0)
			 $headers[] = array('data' => t('Yard Faster'));

		       $result = db_query("Select Round(Fee,2) as Fee,FastCut,SlowCut,Fast_L,Slow_L,Fast_Y,Slow_Y,MtEvent, MtEvX, Sex, Lo_Hi, I_R,Division FROM ".$db_name."mtevente WHERE Meet=".inj_int($_GET['m'])." And MtEv=".inj_int($_GET['ev'])." order by MtEvX");

		       if(!mysql_error())
		       while ($object = mysql_fetch_object($result))
			 {
			    $temp[]= $object->MtEvX;
			    $temp[]= Gender($object->Sex);
			    $temp[]= Age($object->Lo_Hi);
			    $temp[]=$object->Fee;
			    $temp[]=$object->Division;
			    if($QTL>0)
			      $temp[]=get_time($object->Fast_L);
			    if($QTLS>0)
			      $temp[]=get_time($object->Slow_L);
			    if($QTS>0)
			      $temp[]=get_time($object->FastCut);
			    if($QTSS>0)
			      $temp[]=get_time($object->SlowCut);
			if($QTY>0)
			   $temp[]=(get_time($object->Fast_Y));
			 if($QTYS>0)
			   $temp[]=(get_time($object->Slow_Y));

			    $rows[] = $temp;
			    $temp=null;
			 }
		       $output.= theme_table($headers, $rows,null,null);
		       
		       render_page();
?>