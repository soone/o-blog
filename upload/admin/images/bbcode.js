/******************************************************************************
  O-blog System - BB Code Insert
  Modified by: shishirui
*******************************************************************************/

defmode = "normalmode";		// default mode (normalmode, advmode, helpmode)

if (defmode == "advmode") {
        helpmode = false;
        normalmode = false;
        advmode = true;
} else if (defmode == "helpmode") {
        helpmode = true;
        normalmode = false;
        advmode = false;
} else {
        helpmode = false;
        normalmode = true;
        advmode = false;
}
function chmode(swtch){
        if (swtch == 1){
                advmode = false;
                normalmode = false;
                helpmode = true;
                alert(help_mode);
        } else if (swtch == 0) {
                helpmode = false;
                normalmode = false;
                advmode = true;
                alert(adv_mode);
        } else if (swtch == 2) {
                helpmode = false;
                advmode = false;
                normalmode = true;
                alert(normal_mode);
        }
}

function AddText(NewCode) {
        if(document.all){
        	insertAtCaret(document.input.message, NewCode);
        	setfocus();
        } else{
        	document.input.message.value += NewCode;
        	setfocus();
        }
}

function storeCaret (textEl){
        if(textEl.createTextRange){
                textEl.caretPos = document.selection.createRange().duplicate();
        }
}

function insertAtCaret (textEl, text){
        if (textEl.createTextRange && textEl.caretPos){
                var caretPos = textEl.caretPos;
                caretPos.text += caretPos.text.charAt(caretPos.text.length - 2) == ' ' ? text + ' ' : text;
        } else if(textEl) {
                textEl.value += text;
        } else {
        	textEl.value = text;
        }
}

function email() {
        if (helpmode) {
                alert(email_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[email]" + range.text + "[/email]";
	} else if (advmode) {
	      AddTxt="[email] [/email]";
                AddText(AddTxt);
        } else { 
                txt2=prompt(email_normal,""); 
                if (txt2!=null) {
                        txt=prompt(email_normal_input,"name@domain.com");      
                        if (txt!=null) {
                                if (txt2=="") {
                                        AddTxt="[email]"+txt+"[/email]";
                
                                } else {
                                        AddTxt="[email="+txt+"]"+txt2+"[/email]";
                                } 
                                AddText(AddTxt);                
                        }
                }
        }
}


function chsize(size) {
        if (helpmode) {
                alert(fontsize_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[size=" + size + "]" + range.text + "[/size]";
        } else if (advmode) {
                AddTxt="[size="+size+"] [/size]";
                AddText(AddTxt);
        } else {                       
                txt=prompt(fontsize_normal,text_input); 
                if (txt!=null) {             
                        AddTxt="[size="+size+"]"+txt;
                        AddText(AddTxt);
                        AddText("[/size]");
                }        
        }
}

function chfont(font) {
        if (helpmode){
                alert(font_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[font=" + font + "]" + range.text + "[/font]";
        } else if (advmode) {
                AddTxt="[font="+font+"] [/font]";
                AddText(AddTxt);
        } else {                  
                txt=prompt(font_normal,text_input);
                if (txt!=null) {             
                        AddTxt="[font="+font+"]"+txt;
                        AddText(AddTxt);
                        AddText("[/font]");
                }        
        }  
}


function bold() {
        if (helpmode) {
                alert(bold_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[b]" + range.text + "[/b]";
        } else if (advmode) {
                AddTxt="[b] [/b]";
                AddText(AddTxt);
        } else {  
                txt=prompt(bold_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[b]"+txt;
                        AddText(AddTxt);
                        AddText("[/b]");
                }       
        }
}

function italicize() {
        if (helpmode) {
                alert(italicize_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[i]" + range.text + "[/i]";
        } else if (advmode) {
                AddTxt="[i] [/i]";
                AddText(AddTxt);
        } else {   
                txt=prompt(italicize_normal,text_input);     
                if (txt!=null) {           
                        AddTxt="[i]"+txt;
                        AddText(AddTxt);
                        AddText("[/i]");
                }               
        }
}

function quote() {
        if (helpmode){
                alert(quote_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[quote]" + range.text + "[/quote]";
        } else if (advmode) {
                AddTxt="\r[quote]\r[/quote]";
                AddText(AddTxt);
        } else {   
                txt=prompt(quote_normal,text_input);     
                if(txt!=null) {          
                        AddTxt="\r[quote]\r"+txt;
                        AddText(AddTxt);
                        AddText("\r[/quote]");
                }               
        }
}

function chcolor(color) {
        if (helpmode) {
                alert(color_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[color=" + color + "]" + range.text + "[/color]";
        } else if (advmode) {
                AddTxt="[color="+color+"] [/color]";
                AddText(AddTxt);
        } else {  
        txt=prompt(color_normal,text_input);
                if(txt!=null) {
                        AddTxt="[color="+color+"]"+txt;
                        AddText(AddTxt);
                        AddText("[/color]");
                }
        }
}

function center() {
        if (helpmode) {
                alert(center_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[center]" + range.text + "[/center]";
        } else if (advmode) {
                AddTxt="[align=center] [/align]";
                AddText(AddTxt);
        } else {  
                txt=prompt(center_normal,text_input);     
                if (txt!=null) {          
                        AddTxt="\r[align=center]"+txt;
                        AddText(AddTxt);
                        AddText("[/align]");
                }              
        }
}

/*
function hyperlink() {
        if (helpmode) {
                alert(link_help);
        } else if (advmode) {
                AddTxt="[url] [/url]";
                AddText(AddTxt);
        } else { 
                txt2=prompt(link_normal,""); 
                if (txt2!=null) {
                        txt=prompt(link_normal_input,"http://");      
                        if (txt!=null) {
                                if (txt2=="") {
                                        AddTxt="[url]"+txt;
                                        AddText(AddTxt);
                                        AddText("[/url]");
                                } else {
                                        AddTxt="[url="+txt+"]"+txt2;
                                        AddText(AddTxt);
                                        AddText("[/url]");
                                }         
                        } 
                }
        }
}
*/

function getSelectedText() {
	var	post = document.input.message;
	var	selected = '';
	if(post.isTextEdit){ 
		post.focus();
		var	sel	= document.selection;
		var	rng	= sel.createRange();
		rng.colapse;
		if((sel.type ==	"Text" || sel.type == "None") && rng !=	null){
			if(rng.text.length > 0)	selected = rng.text;
		}
	}	
	return selected;
}

function hyperlink() {
	if (helpmode) {
		alert(link_help);
	} else if (getSelectedText()) {
		var	range =	document.selection.createRange();
		range.text = "[url]" + range.text +	"[/url]";
	} else if (advmode)	{
		AddTxt="[url] [/url]";
		AddText(AddTxt);
	} else { 
		txt2=prompt(link_normal,""); 
		if (txt2!=null)	{
			txt=prompt(link_normal_input,"http://");	  
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[url]"+txt;
					AddText(AddTxt);
					AddText("[/url]");
				} else {
					AddTxt="[url="+txt+"]"+txt2;
					AddText(AddTxt);
					AddText("[/url]");
				}		  
			} 
		}
	}
}

function image() {
        if (helpmode){
                alert(image_help);
        } else if (advmode) {
                AddTxt="[img] [/img]";
                AddText(AddTxt);
        } else {  
                txt=prompt(image_normal,"http://");    
                if(txt!=null) {            
                        AddTxt="\r[img]"+txt;
                        AddText(AddTxt);
                        AddText("[/img]");
                }       
        }
}

function flash() {
        if (helpmode){
                alert(flash_help);
        } else if (advmode) {
                AddTxt="[swf] [/swf]";
                AddText(AddTxt);
        } else {  
                txt=prompt(flash_normal,"http://");    
                if(txt!=null) {            
                        AddTxt="\r[swf]"+txt;
                        AddText(AddTxt);
                        AddText("[/swf]");
                }       
        }
}
function wmv() {
        if (helpmode){
                alert(wmv_help);
        } else if (advmode) {
                AddTxt="[wmv] [/wmv]";
                AddText(AddTxt);
        } else {  
                txt=prompt(wmv_normal,"http://");    
                if(txt!=null) {            
                        AddTxt="\r[wmv]"+txt;
                        AddText(AddTxt);
                        AddText("[/wmv]");
                }       
        }
}

function rm() {
        if (helpmode){
                alert(rm_help);
        } else if (advmode) {
                AddTxt="[rm] [/rm]";
                AddText(AddTxt);
        } else {  
                txt=prompt(rm_normal,"http://");    
                if(txt!=null) {            
                        AddTxt="\r[rm]"+txt;
                        AddText(AddTxt);
                        AddText("[/rm]");
                }       
        }
}function code() {
        if (helpmode) {
                alert(code_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[code]" + range.text + "[/code]";
        } else if (advmode) {
                AddTxt="\r[code]\r[/code]";
                AddText(AddTxt);
        } else {   
                txt=prompt(code_normal,"");     
                if (txt!=null) {          
                        AddTxt="\r[code]"+txt;
                        AddText(AddTxt);
                        AddText("[/code]");
                }              
        }
}

function list() {
        if (helpmode) {
                alert(list_help);
        } else if (advmode) {
                AddTxt="\r[list][*][*][*][/list]";
                AddText(AddTxt);
        } else {  
                txt=prompt(list_normal,"");
                while ((txt!="") && (txt!="A") && (txt!="a") && (txt!="1") && (txt!=null)) {
                        txt=prompt(list_normal_error,"");               
                }
                if (txt!=null) {
                        if (txt=="") {
                                AddTxt="\r[list]";
                        } else {
                                AddTxt="\r[list="+txt+"]";
                        } 
                        txt="1";
                        while ((txt!="") && (txt!=null)) {
                                txt=prompt(list_normal_input,""); 
                                if (txt!="") {             
                                        AddTxt+="[*]"+txt+""; 
                                }                   
                        } 
                        AddTxt+="[/list]\r\n";
                        AddText(AddTxt); 
                }
        }
}

function underline() {
        if (helpmode) {
                alert(underline_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[u]" + range.text + "[/u]";
        } else if (advmode) {
                AddTxt="[u] [/u]";
                AddText(AddTxt);
        } else {  
                txt=prompt(underline_normal,text_input);
                if (txt!=null) {           
                        AddTxt="[u]"+txt;
                        AddText(AddTxt);
                        AddText("[/u]");
                }               
        }
}

function separator() {
        if (helpmode) {
                alert(underline_help);
	} else if (document.selection && document.selection.type == "Text") {
		var range = document.selection.createRange();
		range.text = "[u]" + range.text + "[/u]";
        } else if (advmode) {
                AddTxt="[u] [/u]";
                AddText(AddTxt);
        } else {  
                txt=prompt(underline_normal,text_input);
                if (txt!=null) {           
                        AddTxt="[u]"+txt;
                        AddText(AddTxt);
                        AddText("[/u]");
                }               
        }
}

function setfocus() {
        document.input.message.focus();
}

function ctlent(obj) {
	if((event.ctrlKey && window.event.keyCode == 13) || (event.altKey && window.event.keyCode == 83)) {
		//if(validate(this.document.input)) 
		this.document.input.submit();
	}
}

function html_trans(str) {
	str = str.replace(/\r/g,"");
	str = str.replace(/on(load|click|dbclick|mouseover|mousedown|mouseup)="[^"]+"/ig,"");
	str = str.replace(/<script[^>]*?>([\w\W]*?)<\/script>/ig,"");
	
	str = str.replace(/<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/ig,"\n[url=$1]$2[/url]\n");
	
	str = str.replace(/<font[^>]+color=([^ >]+)[^>]*>(.*?)<\/font>/ig,"\n[color=$1]$2[/color]\n");
	
	str = str.replace(/<img[^>]+src="([^"]+)"[^>]*>/ig,"\n[img]$1[/img]\n");
	
	str = str.replace(/<([\/]?)b>/ig,"[$1b]");
	str = str.replace(/<([\/]?)strong>/ig,"[$1b]");
	str = str.replace(/<([\/]?)u>/ig,"[$1u]");
	str = str.replace(/<([\/]?)i>/ig,"[$1i]");
	
	str = str.replace(/&nbsp;/g," ");
	str = str.replace(/&amp;/g,"&");
	str = str.replace(/&quot;/g,"\"");
	str = str.replace(/&lt;/g,"<");
	str = str.replace(/&gt;/g,">");
	
	str = str.replace(/<br>/ig,"\n");
	str = str.replace(/<[^>]*?>/g,"");
	str = str.replace(/\[url=([^\]]+)\]\n(\[img\]\1\[\/img\])\n\[\/url\]/g,"$2");
	str = str.replace(/\n+/g,"\n");
	
	return str;
}

function trans(){
	var str = "";
	rtf.focus();
	rtf.document.body.innerHTML = "";
	rtf.document.execCommand("paste");
	str = rtf.document.body.innerHTML;
	if(str.length == 0) {
		alert("剪切板不存在超文本数据！");
		return "";
	}
	return html_trans(str);
}