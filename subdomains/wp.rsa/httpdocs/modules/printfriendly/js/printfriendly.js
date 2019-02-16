
function printVersion(style)
{
	try
	{
		data = parent.document.getElementById('main').innerHTML;
		
		style='';
		obj = document.styleSheets;
		for(i=0;i<obj.length;i++)
		{
			style+='<link type="'+ obj[i].type +'" rel="stylesheet" media="'+ obj[i].media.mediaText +'" href="' + obj[i].href + '" />\n';
		}
		
		stylesheet = style.split('|');
		
		for(i=0;i<stylesheet.length-1;i++)
			
		if(stylesheet[i] !='')
		style+=('<link type="text/css" rel="stylesheet" media="print" href="' + stylesheet[i] + '" />\n');
		
		data=style+"<body>"+data+"</body>";
		
		parent.document.write(data);
		parent.document.close();
		
		alert("If you like the hilighting to be print, change the print options to print background colors.Please not that you may need to set-up your browser print margins.\nWe Advise you cancel the print and do a print preveiew for page orientation.\n\nNote for sum pages need to change the page orientation\n to landscape allows wide tables (records,entries) to print properly\n\nClick Cancel to the print window, goto print preview, then change the margins acourdingly");
		parent.window.print();
	}
	catch(e)
	{
		//On error redirect to printable server generated page
		alert('Error Browser does nto support, u must set the redirection Page!!');
	}

}

