//Media Link
function playmedia(strID,strType,strURL,intWidth,intHeight) {
	var objDiv=document.getElementById(strID);
	if (!objDiv) return false;
	if (objDiv.style.display!='none') {
		objDiv.innerHTML='';
		objDiv.style.display='none';
	} else {
		objDiv.innerHTML=makemedia(strType,strURL,intWidth,intHeight);
		objDiv.style.display='block';
	}
}

//Media Build
function makemedia (strType,strURL,intWidth,intHeight) {
	var strHtml;
	switch(strType) {
		case 'wmp':
			strHtml="<object width='"+intWidth+"' height='"+intHeight+"' classid='CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6'><param name='url' value='"+strURL+"'/><embed width='"+intWidth+"' height='"+intHeight+"' type='application/x-mplayer2' src='"+strURL+"'></embed></object>";
			break;
		case "wmv":
			strHtml='<div style="height:6px;overflow:hidden"></div><object classid="clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,02,902" type="application/x-oleobject" standby="Loading..." width="'+intWidth+'" height="'+intHeight+'"><param name="FileName" VALUE="'+strURL+'" /><param name="ShowStatusBar" value="-1" /><param name="AutoStart" value="true" /><embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" src="'+strURL+'" autostart="true" width="'+intWidth+'" height="'+intHeight+'" /></object>';
			break;
		case 'swf':
			strHtml="<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='"+intWidth+"' height='"+intHeight+"'><param name='movie' value='"+strURL+"'/><param name='quality' value='high' /><embed src='"+strURL+"' quality='high' type='application/x-shockwave-flash' width='"+intWidth+"' height='"+intHeight+"'></embed></object>";
			break;
		case 'rm':
			strHtml="<object classid='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' height='"+intHeight+"' width='"+intWidth+"' viewastext><param name='_ExtentX' value='12726'><param name='_ExtentY' value='8520'><param name='AUTOSTART' value='0'><param name='SHUFFLE' value='0'><param name='PREFETCH' value='true'><param name='NOLABELS' value='0'><param name='CONTROLS' value='ImageWindow'><param name='CONSOLE' value='_master'><param name='LOOP' value='0'><param name='NUMLOOP' value='0'><param name='CENTER' value='0'><param name='MAINTAINASPECT' value='"+strURL+"'><param name='BACKGROUNDCOLOR' value='#000000'></object><br><object classid='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' height='32' id='Player' width='"+intWidth+"' VIEWASTEXT><param name='_ExtentX' value='18256'><param name='_ExtentY' value='794'><param name='AUTOSTART' value='-1'><param name='SHUFFLE' value='0'><param name='PREFETCH' value='0'><param name='NOLABELS' value='0'><param name='CONTROLS' value='controlpanel'><param name='CONSOLE' value='_master'><param name='LOOP' value='0'><param name='NUMLOOP' value='0'><param name='CENTER' value='0'><param name='MAINTAINASPECT' value='0'><param name='BACKGROUNDCOLOR' value='#000000'><param name='SRC' value='"+strURL+"'></object>";
			break;
		case 'mp3':
			strHtml="<object width='400' height='65' classid='CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6'><param name='url' value='"+strURL+"'/><embed width='400' height='65' type='application/x-mplayer2' src='"+strURL+"'></embed></object>";
			break;
		case 'wma':
			strHtml="<object width='400' height='65' classid='CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6'><param name='url' value='"+strURL+"'/><embed width='400' height='65' type='application/x-mplayer2' src='"+strURL+"'></embed></object>";
			break;
		case 'media':
			strHtml="<object width='"+intWidth+"' height='"+intHeight+"' classid='CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6'><param name='url' value='"+strURL+"'/><embed width='"+intWidth+"' height='"+intHeight+"' type='application/x-mplayer2' src='"+strURL+"'></embed></object>";
			break;
		case "qt":
				strHtml="<embed src='"+strURL+"' autoplay='true' Loop='false' controller='true' playeveryframe='false' cache='false' scale='TOFIT' bgcolor='#000000' kioskmode='false' targetcache='false' pluginspage='http://www.apple.com/quicktime/'>";
				break;
	}
	return strHtml;
}