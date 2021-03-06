<?php
 case 'meet_results':
		    {

			$query = "select SQL_CACHE m.QTSS,m.QTS,m.QTLS,m.QTL, m.MName, m.* FROM ".$tm4db."meet_".$season." as m where m.Meet=%d";
			$result = db_query($query,arg(3));
			$object = db_fetch_object($result);
			drupal_set_title($object->MName.' Meet Results'.' '.arg(1).'-'.(arg(1)+1));
			setseasons_breadcrumb(array(l('Meets','meets/'.arg(1))));
			
			
			
			$type = ($object->Type==null)?'ALL':$object->Type;
			$output.="<table><tr><td><table  width='435px' cellSpacing='5' cellPadding='5' border='0'>";
			$output.="<tr><td width='110'>Starts:</td><td width='50' align='right'>".get_date($object->Start)."</td><td width='115'>Ends:</td><td align='right'>".get_date($object->End)."</td></tr>";
			$output.="<tr><td>Course: </td><td align='right'>".$object->Course."</td><td>Meet Type:</td><td align='right'>".l($type,'meets/'.arg(1).'/'.$type)."</td></tr>";
			$output.="<tr><td>Altitude in Feet:</td><td align='right'>".hasvalue('',$object->Altitude>0)."</td><td>Min Open Age:</td><td align='right'>".hasvalue('',$object->MinAge)."</td></tr>";
			$output.="<tr><td vAlign='top' colspan='4'>Location: ".$object->Location."</td></tr>";
			$output.="</table>";
			$output.="<hr align='left' width='435'>";
			$output.="</tr></table>";
			/*
			$evnt_sql='';
			if(($QTL=$object->QTL)>0)
			$evnt_sql=', Fast_L';
			if(($QTLS=$object->QTLS)>0)
			$evnt_sql=', Slow_L';
			if(($QTS=$object->QTS)>0)
			$evnt_sql=', FastCut';
			if(($QTSS=$object->QTSS)>0)
			$evnt_sql=', Slow_Cut';*/
			
		       $result = db_query("Select Session, MtEvent, Lo_Hi ,concat(MtEv,' ',MtEvX) as MtEv, Sex, Distance, Stroke, I_R, Course,Division FROM ".$tm4db."mtevent_".$season." as e WHERE e.MtEv>0 and  Meet=%d Group by MtEv order by Session, MtEv",arg(3));

		       while ($object = db_fetch_object($result))
			 {
			    $link = 'meets/'.arg(1).'/event_info/'.arg(3).'/'.$object->MtEv;
			    $temp[] = $object->Session;

			    $temp[] = ($object->Events == 1)?$object->MtEv:l($object->MtEv, $link);
			    $temp[] =Gender($object->Sex);
			    $temp[] = Age($object->Lo_Hi);
			    $temp[] =  $object->Distance."m";
			    $temp[] = Stroke($object->Stroke);
			    $temp[] =  IR($object->I_R);
			    $temp[] = ($object->Events==1)?$object->Fee:'';
			    $temp[] = Course(0,$object->Course);
			   /*
				 if($QTL>0)
				   $temp[]=get_time($object->Fast_L);
				 if($QTLS>0)
				   $temp[]=get_time($object->Slow_L);
				 if($QTS>0)
				   $temp[]=get_time($object->FastCut);
				 if($QTSS>0)
				   $temp[]=get_time($object->SlowCut);
			   */
			    $rows[] = $temp;
			    $temp=null;
			 }
		       $output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
		    }
		  break;
		  ?>