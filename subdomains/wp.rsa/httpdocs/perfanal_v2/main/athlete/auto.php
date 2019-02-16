<?php
	
	require('../../main_include.php');
	header("Cache-Control: max-age=300, public"); 
	function drupal_to_js($var) {
		switch (gettype($var)) {
		case 'boolean':
		return $var ? 'true' : 'false'; // Lowercase necessary!
		case 'integer':
		case 'double':
		return $var;
		case 'resource':
		case 'string':
		return '"'. str_replace(array("\r", "\n", "<", ">", "&"),
				      array('\r', '\n', '\x3c', '\x3e', '\x26'),
				      addslashes($var)) .'"';
		case 'array':
		// Arrays in JSON can't be associative. If the array is empty or if it
		// has sequential whole number keys starting with 0, it's not associative
		// so we can go ahead and convert it as an array.
		if (empty ($var) || array_keys($var) === range(0, sizeof($var) - 1)) {
		$output = array();
		foreach ($var as $v) {
		  $output[] = drupal_to_js($v);
		}
		return '[ '. implode(', ', $output) .' ]';
		}
		// Otherwise, fall through to convert the array as an object.
		case 'object':
		$output = array();
		foreach ($var as $k => $v) {
		$output[] = drupal_to_js(strval($k)) .': '. drupal_to_js($v);
		}
		return '{ '. implode(', ', $output) .' }';
		default:
		return 'null';
		}
	}
	
	$auto = array();
	     $name_filter = "( LCASE(CONCAT(CONCAT(a.Last,a.First),a.Last)) like '".str_replace(" ", "%%",(' '.strtolower($arg[2]).' '))."' ) ";
	     $result = db_query("select SQL_CACHE a.Athlete,a.Last,a.First,a.Sex,t.TCode,extract(YEAR FROM from_days(datediff(CURDATE(), a.Birth))) as Age  from ".$db_name."athlete as a inner join ".$db_name."team as t on (a.Team1=t.Team) where ".$name_filter." order by a.Last,a.first limit 10");
	     if(!mysql_error())
	     while($object = mysql_fetch_object($result))
	       $auto [$object->Last.' '.$object->First] = "".$object->Last.", ".$object->First." - <b>".$object->Sex."</b> ".$object->Age." ".$object->TCode." ". $object->WMGroup;
	       echo drupal_to_js($auto);
	     exit();
	     ob_end_flush();

     
?>


