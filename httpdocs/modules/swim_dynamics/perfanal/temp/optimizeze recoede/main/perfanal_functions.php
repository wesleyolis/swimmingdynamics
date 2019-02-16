<?php

function yesno($v){
return($v==0)?'No':'Yes';}

function hasval($a,$v){
return(($v>0)?$a.$v:'--');}

function code_type($v=null){
$c=array('Groups','Sub Groups','School Year','MeetType','Meet Division','WM Groups','WM Sub Groups');
return($v==null)?$c:$c[$v];
}

function course($w,$v=null){
$c=array(0=>array('L'=>'LCM','S'=>'SCM','Y'=>'Yards'),1=>array('L'=>'LCM-50m','S'=>'SCM-25m','Y'=>'Yards','ALL'=>'All Courses'));	
return($v==null)?$cc[$w]:$c[$w][strtoupper($v)];
}

/*note the removal of FEMALE,MALE<<MIXED KEYWORDS*/
function gen($v){
$g=array('X'=>'Mixed','F'=>'Female','M'=>'Male');
return$g[strtoupper($v)];}

function IR($v){
$i=array('I'=>'Indi','R'=>'Reley','L'=>'Lead');
return$i[strtoupper($v)];}

function FP($v){
$f=array('F'=>'Final','P'=>'Prelim','I'=>'Semi-Final');
return $f[strtoupper($v)];}

function stroke($v){
$s=array('','Freestyle','Back','Breast','Butterfly','Medley');
return$s[$v];}

function score($n,$s){
if($s==0)
return '<div align=center>--</div>';
else
{
$nt=array('','','NS ','DNF ','','DQ ');
$sp=($s%100);
$i=($s-$sp)/100;
$ss=($i%60);
$mm=(($i-$ss)/60);
$mm=($mm>9)?$mm:(($mm==0)?'00':'0'.$mm);
$ss=($ss>9)?$ss:(($ss==0)?'00':'0'.$ss);
$sp=($sp>9)?$sp:(($sp==0)?'00':'0'.$sp);
return$nt[$n].$mm.':'.$ss.'.'.$sp;}
}

//replaces Age and Lo_Hi
function age($l,$h=null){
if($l==='*')
return$l;
else{
if($h===null)
{if($l>99){
$h=($l%100);
$l=(($l-$h)/100);}
else{$h=$l;$l=0;}}
return (($l>=$h)?$l.'yrs':(($l>0&$h<99)?$l.'-'.$h:(($l==0&$h==99)?'Open':(($h==99)?$l.' & Over':$h.' & Under'))));}
}

function season_dates($s_dd,$s_mm,$yy,$r_up,$r_feq)
{
$s_e=Date('d/m/Y', mktime(0,0,0,$s_dd,$s_mm,($yy+1)));
$c=getdate();
if($c>=$s_e)
{//archive are archived and return enddate of season
return array('type'=>'archive','date'=>$s_e);
}else{
//current rankings work out dat with settings
return array('type'=>'archive','date'=>$s_e);
}
	
}

/*
$i=0;
$start=time();
while($i<00000)
{
score(0,13698);
$i++;
}
$end=time();
echo "<br/>".($end-$start);*/

echo "Loaded";
?>