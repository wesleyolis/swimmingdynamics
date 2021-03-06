<?php
//Compatiblity functions for drupal
$breadcrumb = null;
function drupal_set_title($str)
{
	global $page_title, $site_title,$config,$page_title_inline,$seas_reg;
	$page_title_inline = ucfirst($config['title']).' '.$str;
	$page_title = $seas_reg.' '.$page_title_inline.' - '.$site_title;
	
	
}
function drupal_goto($url,$param=null,$db=true)
{
	global $config;
	 header('Location: '.$url.(($db==true)?('?db='.$config['db_ident'].'&ss='.$config['seas_curr']):'').(($db==true)?'&':'?').$param) ;
	 
	 exit;
}

function ll($t,$url)
{
	return '<a href=\''.$url.'\'>'.$t.'</a>';	
}

function l2($t,$param=null,$url=null)
{
	global $config;
	return '<a href=\''.$url.'?db='.$config['db_ident'].'&ss='.$config['seas_curr'].(($param!=null)?'&'.$param:'').'\'>'.$t.'</a>';	
}

function l($t,$param=null,$url=null)
{
	global $config;
	return '<a href=\''.$url.'?db='.$config['db_ident'].'&ss='.$config['seas_curr'].(($param!=null)?'&q='.$param:'').'\'>'.$t.'</a>';	
}

function url($param=null,$url=null)
{
	global $config;
	return $url.'?db='.$config['db_ident'].'&ss='.$config['seas_curr'].(($param!=null)?'&q='.$param:'');	
}
function url2($param=null,$url=null)
{
	global $config;
	return $url.'?db='.$config['db_ident'].'&ss='.$config['seas_curr'].(($param!=null)?'&'.$param:'');	
}

function setseasons_breadcrumb($crumb)
{
	global $breadcrumb;
	   $breadcrumb = $crumb;
}
function t($str)
{
	return $str;	
}

function drupal_attributes($attributes = array()) {
  if (is_array($attributes)) {
    $t = '';
    foreach ($attributes as $key => $value) {
      $t .= " $key=".'"'. $value .'"';
    }
    return $t;
  }
}

function theme_image($path, $alt = '', $title = '', $attributes = NULL, $getsize = TRUE) {
  if (!$getsize || (is_file($path) && (list($width, $height, $type, $image_attributes) = @getimagesize($path)))) {
    $attributes = drupal_attributes($attributes);
    $url = ($path);
    return '<img src="'. ($url) .'" alt="'. ($alt) .'" title="'. ($title) .'" '. (isset($image_attributes) ? $image_attributes : '') . $attributes .' />';
  }
}

function _theme_table_cell($cell, $header = FALSE) {
  $attributes = '';

  if (is_array($cell)) {
    $data = isset($cell['data']) ? $cell['data'] : '';
    $header |= isset($cell['header']);
    unset($cell['data']);
    unset($cell['header']);
    $attributes = drupal_attributes($cell);
  }
  else {
    $data = $cell;
  }

  if ($header) {
    $output = "<th$attributes>$data</th>";
  }
  else {
    $output = "<td$attributes>$data</td>";
  }

  return $output;
}

function theme_table($header, $rows, $attributes = array(), $caption = NULL) {

  $output = '<table'. drupal_attributes($attributes) .">\n";
  if (isset($caption)) {
    $output .= '<caption>'. $caption ."</caption>\n";
  }

  // Format the table header:
  if (count($header)) {
    $ts = array();//tablesort_init($header);
    // HTML requires that the thead tag has tr tags in it followed by tbody
    // tags. Using ternary operator to check and see if we have any rows.
    $output .= (count($rows) ? ' <thead><tr>' : ' <tr>');
    foreach ($header as $cell) {
  //    $cell = tablesort_header($cell, $header, $ts);
      $output .= _theme_table_cell($cell, TRUE);
    }
    // Using ternary operator to close the tags based on whether or not there are rows
    $output .= (count($rows) ? " </tr></thead>\n" : "</tr>\n");
  }
  else {
    $ts = array();
  }

  // Format the table rows:
  if (count($rows)) {
    $output .= "<tbody>\n";
    $flip = array('even' => 'odd', 'odd' => 'even');
    $class = 'even';
    foreach ($rows as $number => $row) {
      $attributes = array();

      // Check if we're dealing with a simple or complex row
      if (isset($row['data'])) {
        foreach ($row as $key => $value) {
          if ($key == 'data') {
            $cells = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $cells = $row;
      }
      if (count($cells)) {
        // Add odd/even class
        $class = $flip[$class];
        if (isset($attributes['class'])) {
          $attributes['class'] .= ' '. $class;
        }
        else {
          $attributes['class'] = $class;
        }

        // Build row
        $output .= ' <tr'. drupal_attributes($attributes) .'>';
        $i = 0;
        foreach ($cells as $cell) {
         // $cell = tablesort_cell($cell, $header, $ts, $i++);
          $output .= _theme_table_cell($cell);
        }
        $output .= " </tr>\n";
      }
    }
    $output .= "</tbody>\n";
  }

  $output .= "</table>\n";
  return $output;
}

?>
