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
	if(Request.QueryString("Meet").Count != 0)
	{
	rs = oConn.Execute("SELECT MEET.MName, MEET.[Start], MEET.AgeUp, MEET.[End], IIf(IsNull([Since]),'Not Applicable',[Since]) AS Sinces, IIf([RestrictBest]=True,'Yes','No') AS RestrictBests, IIf(([MinAge]=0) Or (IsNull([MinAge])),'None',[MinAge]) AS MinAges, MEET.Course, IIf(IsNull([Type]) Or [Type]='','Not Specified',[Type]) AS Types, MEET.Location, MEET.Remarks, 'R ' & IIf(IsNull([IndCharge]),'0',[IndCharge]) AS IndCharges, 'R ' & IIf(IsNull([SurCharge]),'0',[SurCharge]) AS SurCharges, 'R ' & IIf(IsNull([RelCharge]),'0',[RelCharge]) AS RelCharges, 'R ' & IIf(IsNull([TeamFee]),'0',[TeamFee]) AS TeamFees, 'R ' & IIf(IsNull([FacilityFee]),'0',[FacilityFee]) AS FacilityFees, MEET.Instructions FROM MEET  WHERE (MEET.Meet =" + Request.QueryString("Meet") + ");");
%>


<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Meet Info - <%=rs.Fields("MName")%></title>

<!--#include virtual="/includes\headers.inc"-->

<script language="javascript">
<!--
	funtion write_table(data,coloms,start_row,end_row,width,height,border,cellspac,cellpad,table_id,table_tags,colom_widths,gen_row_id,gen_col_id,tags,)
	{
		if(start_row = -1)
		start_row = data[0];
		else
		start_row = (start_row * coloms) + 1;
		
		end = (end * coloms);	
		
		//Write Table info
		writeln("<Table width='"+width+"'"+" height='"+height+"'"+" border='"+border+"'" +" cellspacing='"+callspac+"'"+" cellspacing='"+cellpadding+"' "+table_tags+"><tr height='0px'>");
		for(rw=0;rw<coloms;rw++)
		{
			writeln("<td width='" + colom_widths[rw] + "'></td>");
		}
		writeln("</tr>");
		while(start<end)
		{
			line_end = start + coloms;
			writeln("</tr>");
			
			for(start < line_end;start++)
			{
				writeln("<td width='" + colom_widths[rw] + "'>");
				
				writeln("</td>");
			}
			
			writeln("</tr>");
		}
		
		writeln("</Table>");
	}

//-->
</script>

</head>

<body topmargin="0" leftmargin="0" bgcolor="#4698D3">
<table width="780" cellspacing="0" cellpadding="0" id="table1"><tr>
<td align='left' valign='top' height="43">
<div style="width:647px;height:50px;filter:dropShadow(offX=-2,offY=-1,color=0060BF)">
<font face="Arial Rounded MT Bold" size="6" color="#000080">&nbsp;<%=rs.Fields("MName")%></font></div></td></tr><tr><td align='center' valign='top'>
<div align="left">
<table border="0" id="table8" cellspacing="0" cellpadding="0" width="780" height="236">
<tr>
<td valign="top" width="422">
<table border="0" id="table9" cellspacing="0" height="72">
<tr>
<td width="54"><b>Starts:</b></td>
<td width="129"><%=rs.Fields("Start")%></td>
<td width="100"><b>Age-up:</b></td>
<td width="130"><%=rs.Fields("AgeUp")%></td>
</tr>
<tr>
<td><b>Ends:</b></td>
<td><%=rs.Fields("End")%></td>
<td><b>Results Since:</b></td>
<td><%=rs.Fields("Sinces")%></td>
</tr>
<tr>
<td colspan="2"><b>Best Times Only:&nbsp; </b><%=rs.Fields("RestrictBests")%></td>
<td colspan="2"><b>Minimum Open Age:&nbsp; </b><%=rs.Fields("MinAges")%></td>
</tr>
<tr>
<td><b>Course:</b></td>
<td><%=rs.Fields("Course")%></td>
<td><b>Meet Type:</b></td>
<td><%=rs.Fields("Types")%></td>
</tr>
</table>
<hr color="#9999FF" align="left" width="415" size="1" noshade>
<table border="0" cellpadding="0" cellspacing="0" id="table11">
<tr>
<td width="76" valign="top" height="50"><b>Location:</b></td>
<td height="47" width="341" valign="top"><%=rs.Fields("Location")%></td>
</tr>
<tr>
<td  height="50" valign="top"><b>Remarks:</b></td>
<td valign="top"><%=rs.Fields("Remarks")%></td>
</tr>
</table>
<hr color="#9999FF" align="left" width="415" size="1" noshade>
<table border="0" cellpadding="0" cellspacing="0" width="416" id="table12" height="67">
<tr>
<td width="134"><b>Individual Entries:</b></td>
<td width="77"><%=rs.Fields("IndCharges")%></td>
<td width="117"><b>Sure Charge</b></td>
<td><%=rs.Fields("SurCharges")%></td>
</tr>
<tr>
<td width="134"><b>Relay Entries:</b></td>
<td width="77"><%=rs.Fields("RelCharges")%></td>
<td width="117"><b>Team Charge:</b></td>
<td><%=rs.Fields("TeamFees")%></td>
</tr>
<tr>
<td width="134"><b>Facility Charge:</b></td>
<td width="77"><%=rs.Fields("FacilityFees")%></td>
<td width="117">&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
</td>
<td valign="top" height="236" width="358">
<div align="center">
<table  border="4"  style="border-collapse: collapse;" bordercolor="#000080" bordercolorlight="#0060BF" bgcolor="#6699FF"  cellpadding="2" cellspacing="0" width="333" id="table10" height="266">
<tr>
<td height="22" align="center"><font face="Californian FB" size="4" color="#000080"><b>&nbsp;Instructions</b></font></td>
</tr>
<tr>
<td height="4"></td>
</tr>
<tr>
<td valign="top" style="padding: 5px"><%=rs.Fields("Instructions")%></td>
</tr>
</table>
</div></td></tr></table>
</div></td></tr><tr><td align='center' valign='top' height="30">
<font color="#000080" size="5">Events info</font>
<table border=0 cellpadding="0" cellspacing="0" width="719" height="93" id="table18">
	<tr>
		<td height="10">
		</td>
	</tr>
	</table>
<p>&nbsp;</td></tr></table>
<p>&nbsp;</p>
</div>
</body>

</body>

</html>

<%}else{
	
	rs = oConn.Execute("SELECT Meet, MName, Start, [End], Course, Location FROM MEET WHERE ((([Start]) > Date())) ORDER BY Start;");
%>
<HTML>
<HEAD>
<meta http-equiv="Content-Language" content="en-za">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1252">
<TITLE>Meet Information</TITLE>
<!--#include virtual="/includes\headers.inc"--></head>
<body topmargin="0" leftmargin="0">


<table border="0" cellpadding="0" cellspacing="0" style="font-weight: bold; font-size:10pt" width="800">
<tr style="font-weight: bold">
	<td colspan="5" align="center" height="47" valign="top"><font color="#000080" size="6">Meet 
	Information</font></td></tr>
<tr valign="top" height="25"  align="left" style="font-weight: bold; color:#000080">
<td width="300" >Meet</td>
<td width="90">Start Date</td>
<td width="90">End Date</td>
<td width="46">Course</td>
<td width="274">Location</td></tr>

<%while(!rs.eof){%><tr><td><a href="meet_info.asp?Meet=<%=rs.Fields("Meet")%>"><%=rs.Fields("MName")%></a></td><td><%=rs.Fields("Start")%></td><td><%=rs.Fields("End")%></td><td align="center"><%=rs.Fields("Course")%></td>
	<td width="274"><%=rs.Fields("Location")%></td></tr>
<%rs.MoveNext()}%>
</table>
<p>&nbsp;</p>
</body></HTML>
<%
rs.close();}%>