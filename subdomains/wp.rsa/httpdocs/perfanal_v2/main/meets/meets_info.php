<?php
require('../../main_include.php'); 

  $query = "select SQL_CACHE IF(e.Meet Is Null,1,0) as results,if(m.RestrictBest=1,True,False) as RestrictBest2,if(m.EnforceQualifying=1,True,False) as EnforceQualifying2, m.* FROM ".$db_name."meet as m left join ".$db_name."mtevent e on (m.Meet=e.Meet) where m.Meet=".inj_int($_GET['m']);
	     $result = db_query($query);
	     if(!mysql_error())
	     {
	     $object = mysql_fetch_object($result);
	    
	     
	 $display_link = $object->results;

	     drupal_set_title($object->MName.' Meet info'.' '.$_GET['ss'].'-'.($_GET['ss']+1));
	     setseasons_breadcrumb(array(l('Meets','','index.php')));

	   $menu_option = 'info';
	    require('meets_menu.php');
	    
	     $output.=$banner768_160;

	     $type = ($object->Type==null)?'ALL':$object->Type;
	     $output.="<br><table><tr><td><table  width='445px' cellSpacing='5' cellPadding='5' border='0'>";
	     $output.="<tr><td width='110'>Starts:</td><td width='50' align='right'>".get_date($object->Start)."</td><td width='115'>Age-up:</td><td width='60' align='right'>".get_date($object->AgeUp)."</td></tr>";
	     $output.="<tr><td>Ends:</td><td align='right'>".get_date($object->End)."</td><td>Times Since:</td><td align='right'>".get_date($object->Since)."</td></tr>";
	     $output.="<tr><td>Course: </td><td align='right'>".$object->Course."</td><td>Meet Type:</td><td align='right'>".l2($type,'type='.$type,'type.php')."</td></tr>";
	     $output.="<tr><td style='cursor:hand' title='Entry Times must come from Meets of the same Meet Type as this one.'>E.T Same Type:</td><td align='right'>".yesno($object->RestrictBest2)."</td><td>Sanction #:</td><td>".$object->Sanction."</td></tr>";
	     $output.="<tr><td nowrap>Q/Times Enforced:</td><td align='right'>".yesno($object->EnforceQualifying2)."</td><td>Min Open Age:</td><td align='right'>".hasvalue('',$object->MinAge)."</td></tr>";
	     $output.="<tr><td>Altitude in Feet:</td><td align='right'>".hasvalue('',$object->Altitude>0)."</td><td></td><td></td></tr>";
	     $output.="</table>";

	     $output.="<hr align='left' width='445'>";

	     $output.="<table width='445px' cellSpacing='5' cellPadding='5' border='0'>";
	     $output.="<tr><td vAlign='top' width='76'>Location:</td><td vAlign='top'>".$object->Location."</td></tr>";
	     $output.="<tr><td vAlign='top'>Remarks:</td><td vAlign='top'>".$object->Remarks."</td></tr>";
	     $output.="</table>";

	     $output.="<hr align='left' width='445'>";

	     $output.="<table cellSpacing='5' cellPadding='5' border='0' width='445px'>";
	     $output.="<tr><td width='134'>Individual Entries:</td><td width='77' align='right'>".hasvalue('R ',$object->IndCharge)."</td><td width='117'>Surcharge</td><td align='right'>".hasvalue('R ',$object->SurCharge)."</td></tr>";
	     $output.="<tr><td width='134'>Relay Entries:</td><td width='77' align='right'>".hasvalue('R ',$object->RelCharge)."</td><td width='117'>Team Charge:</td><td align='right'>".hasvalue('R ',$object->TeamFee)."</td></tr>";
	     $output.="<tr><td width='134'>Facility Charge:</td><td width='77' align='right'>".hasvalue('R ',$object->FacilityFee)."</td><td></td><td></td></tr>";
	     $output.="</table>";

	     $output.="<hr align='left' width='445'>";

	     $output.="<table cellSpacing='5' cellPadding='5' border='0' width='445px'>";
	     $output.="<td colspan='4'><u>Instructions/Directions:</u></td></tr><tr>";
	     $output.="<td colspan='4'>".$object->Instructions."</td></tr>";
	     $output.="</table></td>";

	     $output.="<td valign='top' style='padding-left:30px;'><b>Time Selection infomation</b><br><br><table border='0' cellpadding='1'>";
	     $output.="<tr><td style='padding-bottom:5px;' width='60'><b>Course</b></td><td width='200'><b>Desciption</b></td></tr>";
	     $output.="<tr><td>L</td><td>Convert all times to LC</td></tr>";
	     $output.="<tr><td>S</td><td>Convert all times to SC</td></tr>";
	     $output.="<tr><td>Y</td><td>Convert all times to Yards</td></tr>";
	     $output.="<tr><td>LO</td><td>&nbsp;LC Times only</td></tr>";
	     $output.="<tr><td>SO</td><td>SC Times only</td></tr>";
	     $output.="<tr><td style='padding-bottom:15px;'>YO</td><td style='padding-bottom:15px;'>Yards Times only</td></tr>";
	     $output.="<tr><td>YSL</td><td valign='top' rowspan='4'>Times selected by course order.<br>Example: LSY order would be<br>L - LC, then S - SC then,<br>&nbsp;Y-Yards</td></tr>";
	     $output.="<tr><td>YLS</td></tr>";
	     $output.="<tr><td>LSY</td></tr>";
	     $output.="<tr><td>SLY</td></tr></table><b>N.B Please note we don't support<br> Yards or Yard Conversions!</b></td></tr></table>";
	     }	
	     
	     render_page();
?>