<?php

	require('../../main_include.php');

	drupal_set_title('Athlete Personal Times - Search Results');
     
	$meta_tags.='<meta name="robots" content="noindex, follow">';


	     $header[] = array('data' => t('Surname'), 'width' => '120px');
	     $header[] = array('data' => t('Name'), 'width' => '120px');
	     $header[] = array('data' => t('Age'), 'width' => '30px');
	     $header[] = array('data' => t('Club'), 'width' => '40px');
	     $header[] = array('data' => t('Catg'), 'width' => '40px');
	    /*
	     if(arg(3)=='ath')
	       {
		  $name_filter = "and a.Birth='".$arg[4]."' and a.Sex='".arg(5)."' and a.Initial='".arg(6)."' and (LCASE(a.Last) like CONCAT('%','".strtolower(arg(7))."%') and LCASE(a.First) like CONCAT('%','".strtolower(arg(8))."%')) ";

	       }
	     else
	       {*/
		     
		  switch($_POST['filterby'])
		    {
		     case 'an':
			 {
			    if ($_POST['filteropt'] !=null)
			      $name_filter = "and LCASE(CONCAT(CONCAT(a.Last,a.First),a.Last)) like '".str_replace(" ", "%%",(' '.strtolower($_POST['filteropt']).' '))."' ";
			    else
			      $name_filter = " ";
			 }
		       break;

		     case 'ln':
			 {
			    $name_filter = " and a.Last like CONCAT('%%','".strtolower($_POST['filteropt'])."%%') ";
			 }
		       break;

		     case 'fn':
			 {
			    if ($_POST['filteropt'] !=null)
			      $name_filter = " and a.First like CONCAT('%%','".strtolower($_POST['filteropt'])."%%') ";
			    else
			      $name_filter = " ";
			 }
		       break;

		     default:
			 {
			    if($_POST['filterby']!=null)
			      $name_filter = "and a.Last like '%%".$_POST['filterby']."%%' ";
			    else
			      $name_filter = " ";
			 }
		       break;
		    }

		  switch($_POST['club'])
		    {
		     case 'ALL':
			 {
			    $clubs_filter = "";
			 }
		       break;

		     default:
			 {
			    if($_POST['club']!=null)
			      $clubs_filter = " and a.Team1=".$_POST['club']." ";
			    else
			      $clubs_filter = "";
			 }
		       break;
		    }

 switch($_GET['filterby'])
{
 case 'an':
 {
    if ($_GET['filteropt'] !=null)
      $name_filter = "and LCASE(CONCAT(CONCAT(a.Last,a.First),a.Last)) like '".str_replace(" ", "%%",(' '.strtolower($_GET['filteropt']).' '))."' ";
 }
   break;

 case 'ln':
 {
    $name_filter = " and a.Last like CONCAT('%%','".strtolower($_GET['filteropt'])."%%') ";
 }
   break;

 case 'fn':
 {
    if ($_GET['filteropt'] !=null)
      $name_filter = " and a.First like CONCAT('%%','".strtolower($_GET['filteropt'])."%%') ";
 }
   break;

 default:
 {
    if($_GET['filterby']!=null)
      $name_filter = "and a.Last like '%%".$_GET['filterby']."%%' ";
 }
   break;
}

switch($_GET['club'])
{
 case 'ALL':
 {
    $clubs_filter = "";
 }
   break;

 default:
 {
    if($_GET['club']!=null)
      $clubs_filter = " and a.Team1=".$_GET['club']." ";
 }
   break;
}
		    
		    
		 
	      // }	


	       $result = db_query("select SQL_CACHE a.Athlete,a.Last,a.First,a.Sex,t.TCode,extract(YEAR FROM from_days(datediff(CURDATE(), a.Birth))) as Age,a.Group  from ".$db_name."athlete as a inner join ".$db_name."team as t on (a.Team1=t.Team) where 1=1 ".$name_filter." ".$clubs_filter.((sizeof($_POST)!=0 && $_POST['group']!='All')?' and a.group=\''.inj_str($_POST['group']).'\' ':'')." ".(($_GET['group'] && $_GET['group']!='All')?' and a.group=\''.inj_str($_GET['group']).'\' ':'')." order by a.Last,a.first");
	      
	         if(!mysql_error())
	     if(mysql_num_rows($result)==1)
	       {	$object = mysql_fetch_object($result);
		       header("Cache-Control: max-age=300, public"); 
		  drupal_goto('times/all_times.php','ath='.$object->Athlete);
	       }
	       else
	   //    header("Cache-Control: max-age=-1");
	      if(!mysql_error())
	     while ($object = mysql_fetch_object($result))
	       {
		  $link = 'ath='.$object->Athlete;

		  if (strtolower($object->Sex) == "m")
		    $rowsM[] = array(l2(t(ucwords(strtolower($object->Last))), $link,'times/all_times.php'), ucwords(strtolower($object->First)), $object->Age,$object->TCode,$object->Group);
		  else
		    $rowsF[] = array(l2(t(ucwords(strtolower($object->Last))), $link,'times/all_times.php'), ucwords(strtolower($object->First)),$object->Age,$object->TCode,$object->Group);
	       }

	     if (!$rowsM)
	       $rowsM[] = array(array('data' => t('No athletes available based the above criteria'), 'colspan' => 5));

	     if (!$rowsF)
	       $rowsF[] = array(array('data' => t('No athletes available based the above criteria'), 'colspan' => 5));

	     $output.= '<table width="100%">';
	     $output.= '<tr>';
	     $output.= '<td align="center" valign="top"><b>Male</b><br><br>'.theme_table($header, $rowsM,array('id'=>'hyper','class'=>'hyper'),null);
	     $output.= '</td>';
	     $output.= '<td align="center" valign="top"><b>Female</b><br><br>'.theme_table($header, $rowsF,array('id'=>'hyper','class'=>'hyper'),null);	;
	     $output.= '</td>';
	     $output.= '</tr>';
	     $output.= '</table>';
	     
	     	render_page();
     
?>
