var total = parent.document.getElementById('total');
if(total !=null)
total.innerHTML='0.00';

/*
if(tables!=null)
for(var i=0;i<tables.length;i++)
	{var table = tables[i];
		if(table.id == 'hyper')
		{tablehyper(table);}
	}
}

function tablehyper(table)
{

	var body =  table.getElementsByTagName('tbody');
	var rows =  body[0].getElementsByTagName('tr');
	for(var i=0;i<rows.length ;i++)
	{
		var row = rows[i];
		hypers = row.getElementsByTagName('a')
		if(hypers.length!=0)
		{
			try{row.style.cursor = 'pointer';}
			catch(eOldIEVersion){row.style.cursor = 'hand';}
			row.orig_class = rows[i].className;
			row.href = hypers[0].href;
			row.onmouseover = function(){this.className = this.orig_class + ' hover';};
			row.onmouseout = function(){this.className = this.orig_class;};
			row.onclick = function(){if(this.href!=null)document.location=this.href};
			
		}
	}
}*/
