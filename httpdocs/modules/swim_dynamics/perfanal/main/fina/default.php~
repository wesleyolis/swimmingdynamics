<?php
switch(arg(1))
     {
      case 'about':
	  {
	     $output.="<div class='tabs'><ul class='tabs primary'>";
	     $output.="<li class='active'>".l('About','fina/about')."</li>";
	     $output.=" <li>".l('Base Times','fina')."</li>";
	     $output.="</ul></div><br><br>";
	     $output.="The FINA Point Scoring allows comparisons of results among different events. The FINA Point<br>";
	     $output.="Scoring assigns point values to swimming performances, more points for world class performances<br>";
	     $output.="typically 1000 or more and fewer points for slower performances.<br>";
	     $output.="Point values are assigned every four years after the Olympic Games. The charts have one set of<br>";
	     $output.="points for Short Course and another for Long Course.<br>";
	     $output.="The scoring is named by the year of the Olympic Games, after which the base times were defined,<br>";
	     $output.="e.g. 'FINA Point Scoring 2004' after the Games of Athen 2004.<br><br>";
	     $output.="<a href='".path_rel()."main/fina/docs/fina.pdf'>Read More - Offical Fina Doc</a>";
	  }
	break;

      case 'base_times':
	  {
	     drupal_set_title("Fina ".arg(2)." ".((arg(3)!='ALL')?Course(1,arg(3))." ":'')."Base Times");

	     drupal_set_breadcrumb(array(l('Base Times','fina')));

	     $headers [] = array('data'=>t('Distance'),'width'=>'60px');
	     $headers [] = array('data'=>t('Stroke'),'width'=>'60px');
	     $headers [] = array('data'=>t('Time'),'width'=>'60px');

	     $results = db_query("Select * From {fina_points} Where Years=".arg(2).((arg(3)!='ALL')?" and Course='".arg(3)."'":'')." and I_R='I' order by Course, I_R,Sex,Stroke,distance");

	     $output.="<table cellpadding='5px' ><tr>";
	     $course = null;
	     $Gender =null;
	     while($object = db_fetch_object($results))
	       {
		  if($course != $object->Course)
		    {
		       $course = $object->Course;
		       if($rows !=null)
			 {
			    $output.=theme('table',$headers,$rows).'</td>';
			    $output.="</tr></table>";
			    $output.="<table cellpadding='5px' ><tr>";
			 }
		       $output.= "<td colspan='2' align='center' class='title'><small>".Course(1,$object->Course)."</small></td></tr><tr>";

		       $rows=null;
		    }

		  if($gender != $object->Sex)
		    {
		       $gender = $object->Sex;
		       if($rows !=null)
			 $output.=theme('table',$headers,$rows).'</td>';
		       $rows=null;
		       $output.= "<td><p class='title' align='center'><small>".Gender($object->Sex)."</small></p>";
		    }

		  $rows[] = array($object->Distance.'m',Stroke($object->Stroke),get_time($object->Score));
	       }

	     $output.=theme('table',$headers,$rows);
	     $output.="</tr></table>";
	  }
	break;

      default:
	  {
	     drupal_set_title("Fina Base Times");

	     $output.="<div class='tabs'><ul class='tabs primary'>";
	     $output.="<li>".l('About','fina/about')."</li>";
	     $output.=" <li class='active'>".l('Base Times','fina')."</li>";
	     $output.="</ul></div>";

	     $output.="<br><br>Please make a selection<br>";

	     $headers[] = array('data'=>t('Years - Course'), 'width'=>'140px');

	     $results = db_query("Select DISTINCT Years,Course From {fina_points}");

	     while($object = db_fetch_object($results))
	       {
		  $rows[] = array(l($object->Years.' - '.Course(1,$object->Course),'fina/base_times/'.$object->Years.'/'.$object->Course));
	       }
	     $output.=theme('table',$headers,$rows);

	  }
	break;
     }
?>