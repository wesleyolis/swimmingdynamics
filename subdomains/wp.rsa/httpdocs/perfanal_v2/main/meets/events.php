<?php

require('../../main_include.php'); 

	$min_display = $config['display_min_display'];

 $query = "select SQL_CACHE  distinct m.QTSS,m.QTS,m.QTLS,m.QTL,IF(e.Meet Is Null,1,0) as results, m.MName FROM ".$db_name."meet as m left join ".$db_name."mtevent as e on (m.Meet=e.Meet) where m.Meet=".inj_int($_GET['m']);
		     
       $result = db_query($query);
       if(!mysql_error())
       {
	       $object = mysql_fetch_object($result);
	       $display_link = $object->results;
	       drupal_set_title($object->MName.' Meet '.($display_link==0?'Results':'Info').' '.$_GET['ss'].'-'.($_GET['ss']+1));
	       setseasons_breadcrumb(array(l2('Meets',null,'index.php')));
	       
	        $menu_option = 'events';
		require('meets_menu.php');

	       $headers[] = array('data' => t('Event'));
	       $headers[] = array('data' => t('Gender'));
	       $headers[] = array('data' => t('Age'));
	       $headers[] = array('data' => t('Distance'));
	       $headers[] = array('data' => t('Stroke'));
	       $headers[] = array('data' => t('I/R'));
	       $headers[] = array('data' => t('Course'));
	       $headers[] = array('data' => t('Div'));

	       $query = "Select DISTINCT MtEvent, IF(Count(MtEvX)=1,Lo_Hi,'*') as Lo_Hi ,MtEv,MtEvX, IF(Count(Distinct Sex)=1,Sex,'X') As Sex, Distance, Stroke, I_R,If(Count(*)>0,Count(*),1) as Events, Course,Division, Sum(Results) as Results FROM ".$db_name."mtevent as e WHERE e.MtEv>0 and  Meet=".inj_int($_GET['m'])." ".($_GET['age']!='All'?(' and ( e.Lo_Hi='.inj_int($_GET['age']).((isset($_GET['op'])==true)?' or e.Lo_Hi=99':'').')'):'')." ".($_GET['gen']!='All'?(" and (e.Sex='X'or e.Sex='".inj_str($_GET['gen'])."')"):'')." ".(($_GET['age']!='All' || (isset($_GET['gp'])==true))?(' Group by MtEv,MtEvX order by Session, MtEv,MtEvX'):' Group by MtEv order by Session, MtEv')." ";
	     
	       $result = db_query($query);
	       if(!mysql_error())
	       while ($object = mysql_fetch_object($result))
		 {
			 
			
			 if($display_link==0 && $object->Results >0)
			 {
				$link = l2(t($object->MtEv.(($object->Events>1 && $object->Results<$min_display)?'*':'')),($object->Events==1?'evx='.$object->MtEvent:'m='.$_GET['m'].'&ev='.$object->MtEv) ,($object->Events>1 && (($object->Results)>=$min_display))?'event.php':(($object->I_R =='I')?'indi_results.php':'relay_results.php'));
			 }
			 else
			 {
				 $link = $object->MtEv;
			 }

			 $rows[] = array($link,Gender($object->Sex), Age($object->Lo_Hi), $object->Distance."m", Stroke($object->Stroke), IR($object->I_R), Course(0,$object->Course),$object->Division);
		 }

			 	$query = "Select distinct Lo_Hi FROM ".$db_name."mtevent as e WHERE Meet=".inj_int($_GET['m'])." and Lo_Hi!=0 order by Lo_Hi";
				$results = db_query($query);
				if(!mysql_error())
				if(mysql_num_rows($results) > 1)
				{

				$output.="<form action='events.php'  method='get'>";
				$output.="<input type='hidden' name='db' value='".$_GET['db']."'>";
				$output.="<input type='hidden' name='ss' value='".$_GET['ss']."'>";
				$output.="<input type='hidden' name='m' value='".$_GET['m']."'>";
				$output.="<select name='age'>";
				$output.="<option value='All' ".($_GET['age']==''?"selected='selected'":'').">All Age Groups</option>";
				
				$open = false;
				while($object = mysql_fetch_object($results))
				{
					if($object->Lo_Hi==99)
					$open = true;
					$output.="<option value='".$object->Lo_Hi."' ".($_GET['age']==$object->Lo_Hi?"selected='selected'":'')." >".Age($object->Lo_Hi)."</option>";
				
				}
				$output.="</select>";
				if($open)
				$output.="<span style='padding:0em 0em 0em 1em;'><input ".(((isset($_GET['op'])==true) && $_GET['age']!='All')?'checked':'')." type='checkbox' name='op'>Include Open Events</span>";
				
				
				
				$output.="<span style='padding:0em 2em 0em 2em;'>Gender&nbsp;<select name='gen'>";
				$output.="<option value='All' ".($_GET['gen']=='All'?"selected='selected'":'').">All</option>";
				$output.="<option value='F' ".($_GET['gen']=='F'?"selected='selected'":'').">Female</option>";
				$output.="<option value='M' ".($_GET['gen']=='M'?"selected='selected'":'').">Male</option>";
				$output.="</select></span>";
				$output.="&nbsp;&nbsp;<input type='submit' value='Filter'/>";
				if($_GET['age']=='All')
				$output.="<br><input ".(((isset($_GET['gp'])==true))?'checked':'')." type='checkbox' name='gp'>Show Sub Age Groups<br>";
				
				
				}
		 
		 
		 $output.="<table width='100%' border='0' cellspacing='0' cellpadding='0' style='border-style: solid; border-width: 0px'><tr valign='top'><td>".theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper','align'=>'left'),null)."</td><td width='170px'>".$banner160_600."</td></tr></table>";
		 
	       
       }
       
       render_page();
?>