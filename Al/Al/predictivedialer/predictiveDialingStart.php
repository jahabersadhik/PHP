<?php
	$date = date("Y-m-d");
	$customerList = mysql_query("select b.*,a.* from current as a JOIN master as b ON(b.phone=a.phone) where a.dialertype='2' AND a.calltype='0' ");
	echo "<table width='100%' cellpadding='2' cellspacing='2'>";
	echo "<tr><td colspan='4'><span id='responseText'></span></td></tr>";
 	echo "<tr>
 			<th width='20%' align='left'>Name</th>
 			<th width='22%' align='left'>EMail</th>
 			<th width='40%' align='left'>Address</th>
 			<th width='15%' align='left'>Phone</th>
 		  </tr>";
 	while($customerListResult = mysql_fetch_row($customerList))
	{
		echo "<tr>
	 			<td width='25%'>$customerListResult[1] $customerListResult[2]</td>
	 			<td width='25%'>$customerListResult[8]</td>
	 			<td width='40%'>$customerListResult[4]</td>
	 			<td width='10%'>$customerListResult[7]</td>
	 		  </tr>";
	}
	echo "</table>";
?>
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<td align="center"><input type="button" name="start" value="Start Dialing" onclick="javascript:predictiveDialing('','','','');" /></td>
</tr>
</table>
<script language="javascript">
function ajaxFunction()
{
	ajaxObject = function() 
	{
		try 
		{ 
			return new ActiveXObject("Msxml2.XMLHTTP.6.0"); 
		} 
		catch(e)
		{
		
			try 
			{ 
				return new ActiveXObject("Msxml2.XMLHTTP.3.0"); 
			} 
			catch(e)
			{
		
				try 
				{ 
					return new ActiveXObject("Msxml2.XMLHTTP"); 
				} 
				catch(e)
				{
		
					try 
					{ 
						return new ActiveXObject("Microsoft.XMLHTTP"); 
					} 
					catch(e)
					{
		
						try 
						{ 
							return new XMLHttpRequest() 
						} 
						catch(e)
						{
		
							throw new Error( "This browser does not support XMLHttpRequest." );
		
						}
					}
				}
			}
		}
	}
	ajax = new ajaxObject();
	return ajax;
}

function predictiveDialing(source,destinationChannel,currentCount,dialingId,calledDateTime,countCRMAssignedTo,loggedInUsers)
{
	document.getElementById('responseText').innerHTML = "Predictive Dialing In Progress";
	var xmlhttp = ajaxFunction();
	if(xmlhttp != null)
	{
		xmlhttp.onreadystatechange=function()
		{
			if(xmlhttp.readyState==4)
			{
				var responseText = xmlhttp.responseText;
				if(responseText!="")
				document.getElementById('responseText').innerHTML = xmlhttp.responseText;
			}
		}
		if(source == '' && destinationChannel == '')
		{
			var url = "predictivedialer/ajaxAdminPredictAndDialing.php";
		}
		else
		{
			var url = "predictivedialer/ajaxAdminPredictAndDialing.php"; 
		}
		var parameters = "";  
		xmlhttp.open("GET", url, true);
		xmlhttp.send(null);
	}
}
</script>
