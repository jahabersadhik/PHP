function ajaxFunction(){
	/*var xmlhttp;
	if (window.XMLHttpRequest){
	  // code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}else if (window.ActiveXObject){
	  // code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}else{
	  alert("Your browser does not support XMLHTTP!");
	}*/
	// create ajaxObject supports all browser with XMLHttpRequest Support
	ajaxObject = function() {
	try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); } catch(e){
	try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); } catch(e){
	try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch(e){
	try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e){
	try { return new XMLHttpRequest() } catch(e){
	throw new Error( "This browser does not support XMLHttpRequest." );
	}}}}}}
	ajax = new ajaxObject(); // use ajaxObject to start XMLHttpRequest() for most browsers
	return ajax;
}
function activate()
{
	var xmlhttp = ajaxFunction();

	
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4){
			if(xmlhttp.responseText){
		  		document.getElementById('responseText').innerHTML = "<font style='color:#669900'>iSystem Configuration Reloaded</font>";
		  		setTimeout('document.getElementById(\'responseText\').innerHTML = \"\"',3000);
			}else{
				document.getElementById('responseText').innerHTML = "<font style='color:#ff0000'>Failed</font>";
				setTimeout('document.getElementById(\'responseText\').innerHTML = \"\"',3000);
			}
		}
	}
	var url = "redirect.php";  
	xmlhttp.open("GET", url, true);
	xmlhttp.send(null);
	
}
function strstr (haystack, needle, bool) {
    var pos = 0;
     
    haystack += '';
    pos = haystack.indexOf( needle );
    if (pos == -1) {
        return false;
    } else{
        if (bool){
            return haystack.substr( 0, pos );
        } else{
            return haystack.slice( pos );
        }
    }
}
function trim(str){
    if(!str || typeof str != 'string')
        return null;
    return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
}
