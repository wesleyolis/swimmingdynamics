<?php

/*
Overall function required to render pages and connect dbs
*/
	function db_query($query,$close=false)
	{
		global $config;
		global $res;
		global $db_link_unic;
	
		
			$db_link_unic = mysql_pconnect($config['db_url'], $config['db_user'], $config['db_pass']);
			if ($db_link_unic != FALSE)
			{
				
			mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'",$db_link_unic);	
			
			$res = mysql_query($query,$db_link_unic);
			
			//Close the database
			if($close)
			{
				mysql_close($db_link_unic);
				
			}
		}
		else
		{
				die('Connected Failed<br/>');
				$res=FALSE;
				$db_link_unic == null;
				
		}
		return $res;
	}
	
	//injection protection
	function inj_str($str)
	{
		$str_reg='/(;|select|update|delete|union|show|inner)/';
		if(strlen($str)>40 || preg_match($str_reg,$str))
		$str='';
		return $str;	
	}
	function inj_num($num)
	{
		if (!is_numeric($num))
		$num='0';
		return $num;	
	}
	function inj_int($num)
	{
		if (!is_numeric($num))
		$num='0';
		return $num;	
	}
	
	
/*
Prefanal custom functions
*/
	
	
function current_page()
{
	$url = $_SERVER['REQUEST_URI'];
	$ex_url = explode('/',$url);
   return str_replace('?q=','',$ex_url[(($ex_url[1]=='perfanal')?2:1)]).'/';
   
}

function yesno($v)
{
   return ($v==0)?'No':'Yes';
}

function hasvalue($a,$v)
{
   return (($v>0)?$a.$v:'--');
}
function Code_Type($v)
{
   $Code[0] = 'Groups';
   $Code[1] = 'Sub Groups';
   $Code[2] = 'School Year';
   $Code[3] = 'Meet Type';
   $Code[4] = 'Meet Division';
   $Code[5] = 'WM Groups';
   $Code[6] = 'WM Sub Groups';

   return $Code[$v];
}

function Course($w,$v)
{
	
   $Course[0][''] = '';
   $Course[0]['L'] = 'LCM';
   $Course[0]['S'] = 'SCM';
   $Course[0]['Y'] = 'Yards';
   $Course[0]['ALL'] = ' - All Courses';
   $Course[1]['L'] = 'LCM-50m';
   $Course[1]['S'] = 'SCM-25m';
   $Course[1]['Y'] = 'Yards';
   
   return $Course[$w][strtoupper($v)];
}

function Gender($v)
{
   $Gender['X'] = 'Mixed';
   $Gender['F'] = 'Female';
   $Gender['M'] = 'Male';
   $Gender['FEMALE'] = 'Female';
   $Gender['MALE'] = 'Male';
   $Gender['MIXED'] = 'Mixed';
   return $Gender[strtoupper($v)];
}

function IR($v)
{
   $IR['I'] = 'Indi';
   $IR['R'] = 'Relay';
   $IR['L'] = 'Lead';
   return $IR[strtoupper($v)];
}

function FP($v)
{
   $FP['F'] = 'Final';
   $FP['P'] = 'Prelim';
   $FP['I'] = 'Semi';
   return $FP[strtoupper($v)];
}

function Stroke($v)
{
   $Stroke = array('','Freestyle','Back','Breast','Butterfly','Medley');
   return $Stroke[$v];
}

//Formating functions

function NT($n)
{$NT = array('','','NS','DNF','','DQ');
   return	$NT[$n];
}

function Score($n,$s)
{
   $s = (NT($n) =='')?get_time($s):''.NT($n).get_time($s).'';
   return $s;
}

function get_time ($Score)
{
   if($Score<=0)
     return ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--';
   else
     { 
	$split = substr($Score, -2);
	$seconds = substr($Score, 0, -2);
	if($seconds<=0)
	$seconds='00';
	if($seconds<3600)
		return Date('i:s',mktime(0,0,$seconds)).'.'.$split;
	else
		return Date('H:i:s', mktime(0,0,$seconds)).'.'.$split;
     }
}


function get_sanc($sanc)
{
	$sanc = explode(',',$sanc);
	if(sizeof($sanc)<=2)
	{
		if(sizeof($sanc)==0)
		return '';
		else
		if($sanc[0]!='')
		return $sanc[0];
	}
	else
	{
		return '*';
	}
}

function Age($Ag)
{
   return ($Ag=='*')?'*':(($Ag>99)?LO_HI(floor($Ag/100),$Ag%100):LO_HI(0,$Ag));//(($Ag==99)?'Open':$Ag.'yrs'));
}

function LO_HI($Lo,$Hi)
{
   return ($Lo>=$Hi)?$Hi.'yrs':(($Lo>0&$Hi<99)?$Lo.'-'.$Hi:(($Lo==0&$Hi==99)?'Open':(($Hi==99)?$Lo.'&nbsp;&&nbsp;Over':$Hi.'&nbsp;&&nbsp;Under')));
}

//Not to self change date formatting to corisponed with drupal date formate.
function get_date($v)
{

   $start_date = explode("-", $v);
   return ($v==null)?'-':(($v=='0000-00-00 00:00:00')?'--': Date('d/m/Y', mktime(0, 0, 0, intval( $start_date[1]),intval( $start_date[2]), intval($start_date[0]))));
}


$Factors = Array(0.8,0.6,1,0.7,0.8);
$Factors_yards = Array( 1.11, 0.875, 1.006 );

function convert_time( $from_course, $to_course, $stroke, $distance, $time, $factors, $factors_yards )
{
    /* Supported Conversions
    from_course | to_course
    Long Course => ShortCourse
    Yards       => ShortCourse
    ShorCourse  => Yards
    ShortCourse => LongCourse
    */

if ( $from_course == $to_course || $time == 0)
	return $time;

$con_factor = $factors[$stroke - 1] * 100/50;


switch ( $to_course )
    {

        case 'L':
            {
		if ( $from_course == 'Y' )
		{
			return 'NT SUP';
			// First convert to yards to Short Course then to long course.
			$time = convert_time( 'Y','S', $stroke, $distance, $time, $factors, $factors_yards );

		}
		if ( $from_course == 'S' || $from_course == 'Y' )
		{
			return floor( $time + ( $distance * $con_factor) );
		}
            }

        case 'S':
            {
		if ( $from_course == 'Y' )
		{
			if ( $distance < 500 )
				return floor( $time * $factors_yards[0]  );
			else if ( $distance < 1650 )
				return floor( $time * $factors_yards[1] );
			else
				return floor( $time * $factors_yards[2] );
		}
		else if ( $from_course == 'L' )
		{
			return floor( $time - ( $distance * $con_factor) );
		}
            }

        case 'Y':
            {

		if ( $from_course == 'L' )
		{
			return 'NT SUP';
			// convert long course to short course then to yards
			$time = floor( $time - ( $distance * $con_factor) );
		}

		if ( $from_course == 'S' || $from_course == 'L' )
		{
			if ( $distance < 500 )
				return floor($time / $factors_yards[0] );
			else if ( $distance < 1650 )
				return floor( $time / $factors_yards[1] );
			else
				return floor( $time / $factors_yards[2] );
		}
            }

	default:
    }

	return 0;


}

function course_conversion($course) // 0= long course 2 short course visavers & yards to short cause
{
	
	
	$factors = 'ELT(r.STROKE,0.8,0.6,1,0.7,0.8)*100/50';
	if($course == null)
	$con='if(r.Course=\'Y\','.'if(r.distance < 500,floor(r.Score * 1.11),if(r.distance < 1650,floor(r.Score*0.875),floor(r.Score*1.006)))'.','.'floor( r.Score + IF(r.Course=\'L\',-1,1)*r.Distance*'.$factors.')'.')';
	else
	if($course=='S')
	$con='if(r.Course=\'Y\','.'if(r.distance < 500,floor(r.Score * 1.11),if(r.distance < 1650,floor(r.Score*0.875),floor(r.Score*1.006)))'.','.'floor( r.Score + IF(r.Course=\'L\',-1,0)*r.Distance*'.$factors.')'.')';
	else
	if($course=='Y')
	$con='if(r.Course=\'Y\','.'r.score'.','.'(r.Score + IF(r.Course=\'L\',-1,0)*r.Distance*'.$factors.')/if(r.distance < 500,1.11,if(r.distance < 1650,0.875,1.006))'.')';
	else
	if($course=='L')
	$con='if(r.Course=\'Y\','.'floor(r.Distance*'.$factors.' + r.score*if(r.distance < 500,1.11,if(r.distance < 1650,0.875,1.006)))'.','.'floor( r.Score + IF(r.Course=\'L\',0,1)*r.Distance*'.$factors.')'.')';

	return $con;
}

function fina_seas($seas)
{
	global $config;
	echo 'Select DISTINCT Years From '.$config['db_name'].'.fina_points where Years <='.inj_int($seas).' order by Years desc';
	$result = db_query('Select DISTINCT Years From '.$config['db_name'].'.fina_points where Years <='.inj_int($seas).' order by Years desc');
	if(!mysql_error())
	if($object = mysql_fetch_object($result))
	return $object->Years;
	
}

?>