function init() {
	tinyMCEPopup.resizeToInnerSize();
}

var charmap = new Array();

// for mor details please see w3c.org
// now here is the complete list ;)

charmap = [
	['&nbsp;',    '&#160;',  true, 'no-break space'],
	['&amp;',     '&#38;',   true, 'ampersand'],
	['&quot;',    '&#34;',   true, 'quotation mark'],
// finance
	['&cent;',    '&#162;',  true, 'cent sign'],
	['&euro;',    '&#8364;', true, 'euro sign'],
	['&pound;',   '&#163;',  true, 'pound sign'],
	['&yen;',     '&#165;',  true, 'yen sign'],
// signs
	['&copy;',    '&#169;',  true, 'copyright sign'],
	['&reg;',     '&#174;',  true, 'registered sign'],
	['&trade;',   '&#8482;', true, 'trade mark sign'],
	['&permil;',  '&#8240;', true, 'per mille sign'],
	['&micro;',   '&#181;',  true, 'micro sign'],
	['&middot;',  '&#183;',  true, 'middle dot'],
	['&bull;',    '&#8226;', true, 'bullet'],
	['&hellip;',  '&#8230;', true, 'three dot leader'],
	['&prime;',   '&#8242;', true, 'minutes / feet'],
	['&Prime;',   '&#8243;', true, 'seconds / inches'],
	['&sect;',    '&#167;',  true, 'section sign'],
	['&para;',    '&#182;',  true, 'paragraph sign'],
	['&szlig;',   '&#223;',  true, 'sharp s / ess-zed'],
// quotations
	['&lsaquo;',  '&#8249;', true, 'single left-pointing angle quotation mark'],
	['&rsaquo;',  '&#8250;', true, 'single right-pointing angle quotation mark'],
	['&laquo;',   '&#171;',  true, 'left pointing guillemet'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ;',    '&#220;',  true, 'U - diaeresis'],
	['&Yacute;',  '&#221;',  true, 'Y - acute'],
	['&Yuml;',    '&#376;',  true, 'Y - diaeresis'],
	['&THORN;',   '&#222;',  true, 'THORN'],
	['&agrave;',  '&#224;',  true, 'a - grave'],
	['&aacute;',  '&#225;',  true, 'a - acute'],
	['&acirc;',   '&#226;',  true, 'a - circumflex'],
	['&atilde;',  '&#227;',  true, 'a - tilde'],
	['&auml;',    '&#228;',  true, 'a - diaeresis'],
	['&aring;',   '&#229;',  true, 'a - ring above'],
	['&aelig;',   '&#230;',  true, 'ligature ae'],
	['&ccedil;',  '&#231;',  true, 'c - cedilla'],
	['&egrave;',  '&#232;',  true, 'e - grave'],
	['&eacute;',  '&#233;',  true, 'e - acute'],
	['&ecirc;',   '&#234;',  true, 'e - circumflex'],
	['&euml;',    '&#235;',  true, 'e - diaeresis'],
	['&igrave;',  '&#236;',  true, 'i - grave'],
	['&iacute;',  '&#237;',  true, 'i - acute'],
	['&icirc;',   '&#238;',  true, 'i - circumflex'],
	['&iuml;',    '&#239;',  true, 'i - diaeresis'],
	['&eth;',     '&#240;',  true, 'eth'],
	['&ntilde;',  '&#241;',  true, 'n - tilde'],
	['&ograve;',  '&#242;',  true, 'o - grave'],
	['&oacute;',  '&#243;',  true, 'o - acute'],
	['&ocirc;',   '&#244;',  true, 'o - circumflex'],
	['&otilde;',  '&#245;',  true, 'o - tilde'],
	['&ouml;',    '&#246;',  true, 'o - diaeresis'],
	['&oslash;',  '&#248;',  true, 'o slash'],
	['&oelig;',   '&#339;',  true, 'ligature oe'],
	['&scaron;',  '&#353;',  true, 's - caron'],
	['&ugrave;',  '&#249;',  true, 'u - grave'],
	['&uacute;',  '&#250;',  true, 'u - acute'],
	['&ucirc;',   '&#251;',  true, 'u - circumflex'],
	['&uuml;',    '&#252;',  true, 'u - diaeresis'],
	['&yacute;',  '&#253;',  true, 'y - acute'],
	['&thorn;',   '&#254;',  true, 'thorn'],
	['&yuml;',    '&#255;',  true, 'y - diaeresis'],
    ['&Alpha;',   '&#913;',  true, 'Alpha'],
	['&Beta;',    '&#914;',  true, 'Beta'],
	['&Gamma;',   '&#915;',  true, 'Gamma'],
	['&Delta;',   '&#916;',  true, 'Delta'],
	['&Epsilon;', '&#917;',  true, 'Epsilon'],
	['&Zeta;',    '&#918;',  true, 'Zeta'],
	['&Eta;',     '&#919;',  true, 'Eta'],
	['&Theta;',   '&#920;',  true, 'Theta'],
	['&Iota;',    '&#921;',  true, 'Iota'],
	['&Kappa;',   '&#922;',  true, 'Kappa'],
	['&Lambda;',  '&#923;',  true, 'Lambda'],
	['&Mu;',      '&#924;',  true, 'Mu'],
	['&Nu;',      '&#925;',  true, 'Nu'],
	['&Xi;',      '&#926;',  true, 'Xi'],
	['&Omicron;', '&#927;',  true, 'Omicron'],
	['&Pi;',      '&#928;',  true, 'Pi'],
	['&Rho;',     '&#929;',  true, 'Rho'],
	['&Sigma;',   '&#931;',  true, 'Sigma'],
	['&Tau;',     '&#932;',  true, 'Tau'],
	['&Upsilon;', '&#933;',  true, 'Upsilon'],
	['&Phi;',     '&#934;',  true, 'Phi'],
	['&Chi;',     '&#935;',  true, 'Chi'],
	['&Psi;',     '&#936;',  true, 'Psi'],
	['&Omega;',   '&#937;',  true, 'Omega'],
	['&alpha;',   '&#945;',  true, 'alpha'],
	['&beta;',    '&#946;',  true, 'beta'],
	['&gamma;',   '&#947;',  true, 'gamma'],
	['&delta;',   '&#948;',  true, 'delta'],
	['&epsilon;', '&#949;',  true, 'epsilon'],
	['&zeta;',    '&#950;',  true, 'zeta'],
	['&eta;',     '&#951;',  true, 'eta'],
	['&theta;',   '&#952;',  true, 'theta'],
	['&iota;',    '&#953;',  true, 'iota'],
	['&kappa;',   '&#954;',  true, 'kappa'],
	['&lambda;',  '&#955;',  true, 'lambda'],
	['&mu;',      '&#956;',  true, 'mu'],
	['&nu;',      '&#957;',  true, 'nu'],
	['&xi;',      '&#958;',  true, 'xi'],
	['&omicron;', '&#959;',  true, 'omicron'],
	['&pi;',      '&#960;',  true, 'pi'],
	['&rho;',     '&#961;',  true, 'rho'],
	['&sigmaf;',  '&#962;',  true, 'final sigma'],
	['&sigma;',   '&#963;',  true, 'sigma'],
	['&tau;',     '&#964;',  true, 'tau'],
	['&upsilon;', '&#965;',  true, 'upsilon'],
	['&phi;',     '&#966;',  true, 'phi'],
	['&chi;',     '&#967;',  true, 'chi'],
	['&psi;',     '&#968;',  true, 'psi'],
	['&omega;',   '&#969;',  true, 'omega'],
// symbols
	['&alefsym;', '&#8501;', false,'alef symbol'],
	['&piv;',     '&#982;',  false,'pi symbol'],
	['&real;',    '&#8476;', false,'real part symbol'],
	['&thetasym;','&#977;',  false,'theta symbol'],
	['&upsih;',   '&#978;',  false,'upsilon - hook symbol'],
	['&weierp;',  '&#8472;', false,'Weierstrass p'],
	['&image;',   '&#8465;', false,'imaginary part'],
// arrows
	['&larr;',    '&#8592;', true, 'leftwards arrow'],
	['&uarr;',    '&#8593;', true, 'upwards arrow'],
	['&rarr;',    '&#8594;', true, 'rightwards arrow'],
	['&darr;',    '&#8595;', true, 'downwards arrow'],
	['&harr;',    '&#8596;', true, 'left right arrow'],
	['&crarr;',   '&#8629;', false,'carriage return'],
	['&lArr;',    '&#8656;', false,'leftwards double arrow'],
	['&uArr;',    '&#8657;', false,'upwards double arrow'],
	['&rArr;',    '&#8658;', false,'rightwards double arrow'],
	['&dArr;',    '&#8659;', false,'downwards double arrow'],
	['&hArr;',    '&#8660;', false,'left right double arrow'],
	['&there4;',  '&#8756;', false,'therefore'],
	['&sub;',     '&#8834;', false,'subset of'],
	['&sup;',     '&#8835;', false,'superset of'],
	['&nsub;',    '&#8836;', false,'not a subset of'],
	['&sube;',    '&#8838;', false,'subset of or equal to'],
	['&supe;',    '&#8839;', false,'superset of or equal to'],
	['&oplus;',   '&#8853;', false,'circled plus'],
	['&otimes;',  '&#8855;', false,'circled times'],
	['&perp;',    '&#8869;', false,'perpendicular'],
	['&sdot;',    '&#8901;', false,'dot operator'],
	['&lceil;',   '&#8968;', false,'left ceiling'],
	['&rceil;',   '&#8969;', false,'right ceiling'],
	['&lfloor;',  '&#8970;', false,'left floor'],
	['&rfloor;',  '&#8971;', false,'right floor'],
	['&lang;',    '&#9001;', false,'left-pointing angle bracket'],
	['&rang;',    '&#9002;', false,'right-pointing angle bracket'],
	['&loz;',     '&#9674;', true,'lozenge'],
	['&spades;',  '&#9824;', false,'black spade suit'],
	['&clubs;',   '&#9827;', true, 'black club suit'],
	['&hearts;',  '&#9829;', true, 'black heart suit'],
	['&diams;',   '&#9830;', true, 'black diamond suit'],
	['&ensp;',    '&#8194;', false,'en space'],
	['&emsp;',    '&#8195;', false,'em space'],
	['&thinsp;',  '&#8201;', false,'thin space'],
	['&zwnj;',    '&#8204;', false,'zero width non-joiner'],
	['&zwj;',     '&#8205;', false,'zero width joiner'],
	['&lrm;',     '&#8206;', false,'left-to-right mark'],
	['&rlm;',     '&#8207;', false,'right-to-left mark'],
	['&shy;',     '&#173;',  false,'soft hyphen']
];

function renderCharMapHTML() {
	var charsPerRow = 20, tdWidth=20, tdHeight=20;
	var html = '<table border="0" cellspacing="1" cellpadding="0" width="' + (tdWidth*charsPerRow) + '"><tr height="' + tdHeight + '">';
	var cols=-1;
	for (var i=0; i<charmap.length; i++) {
		if (charmap[i][2]==true) {
			cols++;
			html += ''
				+ '<td width="' + tdWidth + '" height="' + tdHeight + '" class="charmap"'
				+ ' onmouseover="this.className=\'charmapOver\';'
				+ 'previewChar(\'' + charmap[i][1].substring(1,charmap[i][1].length) + '\',\'' + charmap[i][0].substring(1,charmap[i][0].length) + '\',\'' + charmap[i][3] + '\');"'
				+ ' onmouseout="this.className=\'charmap\';"'
				+ ' nowrap="nowrap" onclick="insertChar(\'' + charmap[i][1].substring(2,charmap[i][1].length-1) + '\');"><a style="text-decoration: none;" onfocus="previewChar(\'' + charmap[i][1].substring(1,charmap[i][1].length) + '\',\'' + charmap[i][0].substring(1,charmap[i][0].length) + '\',\'' + charmap[i][3] + '\');" href="javascript:insertChar(\'' + charmap[i][1].substring(2,charmap[i][1].length-1) + '\');" onclick="return false;" onmousedown="return false;" title="' + charmap[i][3] + '">'
				+ charmap[i][1]
				+ '</a></td>';
			if ((cols+1) % charsPerRow == 0)
				html += '</tr><tr height="' + tdHeight + '">';
		}
	 }
	if (cols % charsPerRow > 0) {
		var padd = charsPerRow - (cols % charsPerRow);
		for (var i=0; i<padd-1; i++)
			html += '<td width="' + tdWidth + '" height="' + tdHeight + '" class="charmap">&nbsp;</td>';
	}
	html += '</tr></table>';
	document.write(html);
}

function insertChar(chr) {
	tinyMCEPopup.execCommand('mceInsertContent', false, '&#' + chr + ';');

	// Refocus in window
	if (tinyMCEPopup.isWindow)
		window.focus();

	tinyMCEPopup.close();
}

function previewChar(codeA, codeB, codeN) {
	var elmA = document.getElementById('codeA');
	var elmB = document.getElementById('codeB');
	var elmV = document.getElementById('codeV');
	var elmN = document.getElementById('codeN');

	if (codeA=='#160;') {
		elmV.innerHTML = '__';
	} else {
		elmV.innerHTML = '&' + codeA;
	}

	elmB.innerHTML = '&amp;' + codeA;
	elmA.innerHTML = '&amp;' + codeB;
	elmN.innerHTML = codeN;
}
