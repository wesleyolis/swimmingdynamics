//setTimeout('attachel();',200);

attachel();

function attachel()
{
var tables = parent.document.getElementsByTagName('table');
if(tables!=null)
for(var i=0;i<tables.length;i++)
	{var table = tables[i];
		if(table.id.indexOf('hyper')>=0)
		{tablehyper(table);}
		if(table.id.indexOf('total')>=0)
		{tabletotal(table);}

	}
}

function tablehyper(table)
{

	var body =  table.getElementsByTagName('tbody');
	var rows =  body[0].getElementsByTagName('tr');
	var stop = ((rows.length <200)?rows.length:200);
	for(var i=0;i<stop;i++)
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
}


function get_para(str,para,token)
{
	pos1 = str.indexOf(para);
	pos2 = str.indexOf(token,pos1);
	if(pos2<0)
	pos2= str.length;
	return str.substring((pos1+para.length),pos2);
}

function table_total(dis)
{this.total = 0;
this.ds = parent.document.getElementById(dis);
this.ds.innerHTML = '0';
};
table_total.prototype.add = function (value)
{this.total+=(value*1);
this.ds.innerHTML=this.total};

table_total.prototype.sub = function (value)
{this.total-=(value*1);
this.ds.innerHTML=this.total};

table_total.prototype.check = function (obj,value)
{if(obj.checked){this.add((value))}else{this.sub(value)}};

function tabletotal(table)
{
	var a_col = get_para(table.id,'A','_');
	var c_col = get_para(table.id,'C','_');
	var t = new table_total(get_para(table.id,'D','_'));
	
	var body =  table.getElementsByTagName('tbody');
	var rows =  body[0].getElementsByTagName('tr');
	for(var i=0;i<rows.length ;i++)
	{
		var row = rows[i];
		cols = row.getElementsByTagName('td');
		if(cols.length>=a_col & cols.length>=c_col)
		{		
		
			var col_a = cols[a_col];
			var col_c = cols[c_col];
			var value = (col_a.innerHTML*1);
			var check = col_c.getElementsByTagName('input');
			if(check.length>0)
			{		
			if(check[0].type=='checkbox')
			var ch = check[0]; 
			ch.onclick=function (){t.check(this,value)};
			if(ch.checked)
			t.total+=value;
			}
		}
	}
	t.ds.innerHTML=t.total;
}



