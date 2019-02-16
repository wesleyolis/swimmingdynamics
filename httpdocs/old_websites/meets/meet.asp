<%@ Language=JavaScript %>
<%
Response.CacheControl = "Public";
Response.Expires = 5760;
	
function CTime (Score)
{
if(Score !=0)
{
if(Score<6000)
{t = (Score/100);
if(t <10.0)
{t ="0"+t;}
if(t.toString().length == 2)
{t = t + ".00";}else{
if(t.toString().length<5)
{t=t+"0";}}
}
else
{
s = (Score % 100);
if(s==0){s=".00"}else{
if(s <10){s = "0" + s;}}
if(s.toString().length<2){s = s + "0";}
Score = ((Score -s) / 100);
m = (Score % 60);
if(m < 10)
{m = "0" + m;}
Score = ((Score - m)/60);
h = (Score % 60);
if(h.toString().length<2){h = "0" + h;}
t = h + ":" + m + "." + s;}}
else{t = ""}
return t;}

function Age(Lo,Hi)
{if(Lo <  Hi)
{
if(Lo > 0 & Hi < 99)
{
age = Lo + " - " + Hi + "yrs";
}else
{
if(Hi == 99 & Lo >0)
{
age = Lo + "&nbsp;&amp;&nbsp;Over";
}
else
{
if(Lo == 0 & Hi < 99)
{
age = Hi + "&nbsp;&amp;&nbsp;Under";
}
else
{
if(Lo == 0 & Hi == 99)
{
age = "Open";
}}}}}else{age = Hi + "yrs";}

return age;
}

var strokes = new Array("","Freestyle","Back","Breast","Butterfly","Medley");
	
	var oConn;	
	var rs;		
	var filePath;	
	filePath = Server.MapPath("../Swim.mdb");
	oConn = Server.CreateObject("ADODB.Connection");
	oConn.Open("Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" +filePath);
	if((Request.QueryString("Meet").Count == 0 || Request.QueryString("MName").Count == 0) & Request.QueryString("MtEvent").Count == 0)
	{
	rs = oConn.Execute("SELECT Meet, MName, Start, [End], Course, Location FROM MEET WHERE ((([Start]) < Date())) ORDER BY Start DESC;");
%>
<HTML>
<HEAD>
<meta http-equiv="Content-Language" content="en-za">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1252">
<TITLE>Meet Results</TITLE>
<link rel="stylesheet" type="text/css" href="../styles.css">

<script src="../menu.asp" language="javascript" type="Text/Javascript">
</script>

<script language="javascript" type="Text/Javascript">
makemenu(1);
</script>
</head>
<body topmargin="0" leftmargin="0">


<table border="0" cellpadding="0" cellspacing="0" style="font-weight: bold; font-size:10pt" width="800">
<tr style="font-weight: bold">
	<td colspan="5" align="center" height="47" valign="top"><font color="#000080" size="6">Meet Results</font></td></tr>
<tr valign="top" height="25"  align="left" style="font-weight: bold; color:#000080">
<td width="300" >Meet</td>
<td width="90">Start Date</td>
<td width="90">End Date</td>
<td width="46">Course</td>
<td width="274">Location</td></tr>

<%while(!rs.eof){%><tr><td><a href="meet.asp?Meet=<%=rs.Fields("Meet")%>&MName=<%=rs.Fields("MName")%>"><%=rs.Fields("MName")%></a></td><td><%=rs.Fields("Start")%></td><td><%=rs.Fields("End")%></td><td align="center"><%=rs.Fields("Course")%></td>
	<td width="274"><%=rs.Fields("Location")%></td></tr>
<%rs.MoveNext()}%>
</table>
<p>&nbsp;</p>
</body></HTML>
<%
rs.close();
}
else
{
if(Request.QueryString("MtEvent").Count == 0  & Request.QueryString("Meet").Count != 0)
{
rs = oConn.Execute("SELECT MTEVENT.MtEvent, MTEVENT.[Session], MTEVENT.MtEv, MTEVENT.MtEvX, Int([Lo_Hi]/100) AS Lo_Age, Int([Lo_Hi] Mod 100) AS Hi_Age, MTEVENT.Stroke, MTEVENT.Distance, MTEVENT.[Sex], MTEVENT.[I_R] FROM MTEVENT WHERE (((MTEVENT.Meet)=" + Request.QueryString("Meet") + ") And (MTEVENT.[I_R] = 'I')) ORDER BY MTEVENT.MtEv, MTEVENT.MtEvX;");
%>
<HTML>
<HEAD>
<meta http-equiv="Content-Language" content="en-za">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1252">
<TITLE>Meet Results Events - <%=Request.QueryString("MName")%></TITLE>
<link rel="stylesheet" type="text/css" href="../styles.css">

<script src="../menu.asp" language="javascript" type="Text/Javascript">
</script>

<script language="javascript" type="Text/Javascript">
makemenu(1);
</script>
<script language="javascript">
<!--
MName = '<%=Request.QueryString("MName")%>';
function g(url)
{self.location.replace("meet.asp?Meet=<%=Request.QueryString("Meet")%>&MtEvent=" + url+ "&MName=" + MName );}
function o(row)
{document.getElementById(row).style.background  ='6699FF';
document.getElementById(row).style.color  ='000000';}
function t(row)
{document.getElementById(row).style.background ='';
document.getElementById(row).style.color ='000000';}
//-->
</script>
</head>
<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="799" id="table3">
	<tr>
		<td><div align="center">
<table border="0" cellpadding="0" cellspacing="0" width="460" id="table1" style="font-weight: bold">
<tr style="color: #000080; font-weight: bold" >
<td colspan="8" height="49" align="center"><font size="5"><%=Request.QueryString("MName")%>&nbsp;Events</font></td> 
</tr>
<%if(!rs.eof){%>
<tr style="color: #000080; font-weight: bold" >
	<td height="25" colspan="2" align="center">
	<a href="meet.asp?Meet=<%=Request.QueryString("Meet")%>">Meet List (Back)</a></td> 
	<td height="25" colspan="2" align="center">
	<a href="meet_info.asp?Meet=<%=Request.QueryString("Meet")%>">Meet Info</a></td> 
	<td height="25" colspan="2" align="center">
	<a href="meet.asp?Meet=<%=Request.QueryString("Meet")%>&MtEvent=ALL&MName=<%=Request.QueryString("MName")%>">View All</a></td>  
	<td height="25" colspan="2" align="center">
	<a href="meet_report.asp?Meet=<%=Request.QueryString("Meet")%>&MtEvent=ALL&MName=<%=Request.QueryString("MName")%>">
	Filter Report</a></td> </tr>
	
<tr style="color: #000080; font-weight: bold" ><td width="91" height="25">Event</td> 
	<td width="82" height="25" colspan="2">Gender</td>
	<td width="77" height="25">Age</td><td width="93" height="25">Distance</td>
	<td width="100" height="25" colspan="2">Stroke</td>
	<td height="25" width="73">I/R</td></tr>
	
<%
r = 1;
while(!rs.eof){%><tr onclick="g('<%=rs.Fields("MtEvent")%>')" onmouseover="o('r<%=r%>')" onmouseout="t('r<%=r%>')" id="r<%=r%>"><td><%=rs.Fields("MtEv") + " " + rs.Fields("MtEvX")%></td>
	<td colspan="2"><%if(rs.Fields("Sex").Value == "M"){%>Male<%}else{%>Female<%}%></td><td><%=Age(rs.Fields("Lo_Age"),rs.Fields("Hi_Age"))%></td><td><%=rs.Fields("Distance")%>m</td>
	<td colspan="2"><%=strokes[rs.Fields("Stroke")]%></td><td width="73"><%=rs.Fields("I_R")%></td></tr>
<%rs.MoveNext();r++;}}else{%><tr><td colspan="8" align="center">Sorry there are no events were found for this meet</td></tr><%}%>
</table>
</div>
</td>
	</tr>
</table>
<p>&nbsp;</p>
</body></HTML>

<%}else{
if(Request.QueryString("MtEvent").Count() != 0)
{
var evnt = "", NS = "",F_P="", query =  "WHERE (((MTEVENT.MtEvent)=" + Request.QueryString("MtEvent") + ") And ((MTEVENT.I_R) ='I'))";

if(Request.QueryString("MtEvent") == "ALL")
{
query = "WHERE (((RESULT.Meet)=" + Request.QueryString("Meet") + ") And ((MTEVENT.I_R) ='I'))";
}

//query = "WHERE ((MTEVENT.MtEvent)=18373)";
//rs = oConn.Execute("SELECT Athlete.Last, Athlete.First, Athlete.Sex, Athlete.TAbbr, RESULT.AGE, RESULT.PLACE, RESULT.SCORE, IIf(RESULT.POINTS = 0,'',[RESULT.POINTS]) as POINTS, RESULT.F_P, ([MtEv] & ' ' & [MtEvX]) AS Evnt, Int([Lo_Hi]/100) AS Lo_Age, Int([Lo_Hi] Mod 100) AS Hi_Age, MTEVENT.Stroke, MTEVENT.Distance , MTEVENT.Sex As EvSex, MTEVENT.[I_R], MTEVENT.[Session] FROM (Athlete INNER JOIN RESULT ON Athlete.Athlete = RESULT.ATHLETE) INNER JOIN MTEVENT ON RESULT.MTEVENT = MTEVENT.MtEvent " + query + " ORDER BY ([MtEv] & ' ' & [MtEvX]), RESULT.F_P, RESULT.PLACE;");
//rs = oConn.Execute("SELECT Athlete.Last, Athlete.First, Athlete.[Sex], RESULT.[AGE], RESULT.PLACE, RESULT.SCORE, IIf(RESULT.POINTS=0,'',[RESULT.POINTS]) AS POINTS, RESULT.F_P, (MTEVENT.[MtEv] & ' ' & MTEVENT.[MtEvX]) AS Evnt, MTEVENT.[Lo_Hi]/100 AS Lo_Age, MTEVENT.[Lo_Hi] Mod 100 AS Hi_Age, MTEVENT.Stroke, MTEVENT.Distance, MTEVENT.Sex AS EvSex, MTEVENT.[I_R], MTEVENT.Session, TEAM.[TCode] & '-' & TEAM.[LSC] AS TName FROM TEAM INNER JOIN ((Athlete INNER JOIN RESULT ON Athlete.Athlete = RESULT.ATHLETE) INNER JOIN MTEVENT ON RESULT.MTEVENT = MTEVENT.MtEvent) ON TEAM.Team = Athlete.Team1 WHERE (((MTEVENT.MtEvent)=16799)) ORDER BY (MTEVENT.[MtEv] & ' ' & MTEVENT.[MtEvX]), RESULT.F_P, RESULT.PLACE;");

rs = oConn.Execute("SELECT Athlete.Athlete, Athlete.[Last], Athlete.First, Athlete.[Sex], TEAM.TCode & '-' & TEAM.[LSC] AS TCode, RESULT.[AGE], RESULT.SCORE, IIf(RESULT.POINTS=0,'',[RESULT.POINTS]) AS POINTS, ([MtEv] & ' ' & [MtEvX]) AS [Evnt], RESULT.[F_P], RESULT.PLACE, Int(MTEVENT.[Lo_Hi]/100) AS Lo_Age, Int(MTEVENT.[Lo_Hi] Mod 100) AS Hi_Age, MTEVENT.Stroke, MTEVENT.Distance, MTEVENT.[Sex] AS [EvSex], MTEVENT.[I_R] FROM TEAM INNER JOIN ((Athlete INNER JOIN RESULT ON Athlete.Athlete = RESULT.ATHLETE) INNER JOIN MTEVENT ON RESULT.MTEVENT = MTEVENT.MtEvent) ON TEAM.Team = Athlete.Team1 " + query + " ORDER BY [MtEv], [MtEvX], RESULT.F_P, RESULT.PLACE;");


%>
<html>

<head>
<meta http-equiv="Content-Language" content="en-za">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Meet Results P.R-<%=Request.QueryString("MName")%></title>

<link rel="stylesheet" type="text/css" href="../styles.css">

<script src="../menu.asp" language="javascript" type="Text/Javascript">
</script>

<script language="javascript" type="Text/Javascript">
makemenu(1);
</script>

<script language="javascript">
<!--
function r(ath)
{
document.location.replace("../athlete_times.asp?Athlete=" + ath + "&Toptimes=1");
}
//-->
</script>

</head>
<body  topmargin="0" leftmargin="0">

<table border="0" cellpadding="0" cellspacing="0" width="799" id="table1">
	<tr>
		<td align="center"><font size="5"  style="color: #000080; font-weight: bold" ><%=Request.QueryString("MName")%></font></td>
	</tr>
	<tr>
	<td align="center">
	<a href="meet.asp?Meet=<%=Request.QueryString("Meet")%>&MName=<%=Request.QueryString("MName")%>">Events List (Back)</a></td></tr>
	<tr>
		<td align="center">
		<table border="0" cellpadding="0" cellspacing="0" width="499" id="table2">
			<tr  height ='0'><td width="31">&nbsp;</td><td width="181">&nbsp;</td>
				<td width="41">&nbsp;</td><td width="37">&nbsp;</td>
				<td width="94">&nbsp;</td><td width="76">&nbsp;</td>
				<td width="39">&nbsp;</td></tr>
				
				<%
				if(!rs.eof)
				{
				while(!rs.eof)
				{
				
				if(rs.Fields("Evnt").Value != evnt)
				{
				evnt = rs.Fields("Evnt").Value;				%>
				
				<%=NS%>
<tr><td colspan="7">&nbsp;</td></tr>
<tr height="15px"   bgcolor="#6699FF">
<td colspan="7">
<table  cellpadding="0" cellspacing="0" width="445" style="font-weight: bold">
<tr><td  style="font-size: 14px" width="97">Event:&nbsp<%=rs.Fields("Evnt")%></td>
<td width="67"><%if(rs.Fields("EvSex").Value == "M"){%>Male<%}else{%>Female<%}%></td>
<td width="96"><%=Age(rs.Fields("Lo_Age"),rs.Fields("Hi_Age"))%></td>
<td width="53"><%=rs.Fields("Distance")%>m</td><td><%=strokes[rs.Fields("Stroke")]%></td>
<td width="19"><%=rs.Fields("I_R")%></td></tr></table></td></tr><%
				NS  = "";
				F_P = "";
				}
				
				
					if(rs.Fields("F_P").Value != F_P)
					{
					%>
<%=NS%><tr height="24px">
<td colspan="7" align="center" valign="bottom" style="border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom-style: dashed; border-bottom-width: 1px; font-size:14px"><b><%if(rs.Fields("F_P")=='F'){Response.write("Final");}else{if(rs.Fields("F_P")=='P'){Response.write("Prelim");}else{Response.write("Semi");}}%></b></td></tr><%
					F_P = rs.Fields("F_P").Value;
					NS  = "";
					}
					
					if((rs.Fields("PLACE").Value == 0))
					{
						NS += "<tr><td></td><td><a href='javascript:r(" + rs.Fields("Athlete") + ")'>" + rs.Fields("Last") + ",&nbsp" + rs.Fields("First") + "</a></td><td>" + rs.Fields("Sex") + "</td><td>" + rs.Fields("Age") + "</td><td>" + rs.Fields("TCode") + "</td><td>" + CTime(rs.Fields("SCORE")) + "</td><td></td></tr>";
					}
					else
					{
					%><tr>
<td><%=rs.Fields("PLACE")%></td>
<td><a href='javascript:r(<%=rs.Fields("Athlete")%>)'><%=rs.Fields("Last")%>, &nbsp;<%=rs.Fields("First")%></a></td>
<td><%=rs.Fields("Sex")%></td>
<td><%=rs.Fields("Age")%></td>
<td><%=rs.Fields("TCode")%></td>
<td><%=CTime(rs.Fields("SCORE"))%></td>
<td><%=rs.Fields("POINTS")%></td>
</tr><%}
			
			
			rs.MoveNext();}}else{%><tr><td colspan='7' align='center'>No Results Found</td></tr><%}%></table>
</td>
</tr>
</table>
<br><br><br>
</body>
</html>


<%}}}oConn.close();
%>