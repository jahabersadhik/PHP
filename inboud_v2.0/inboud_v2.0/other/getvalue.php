<?php
	include "../functions.php";
	include "../connection.php";
	session_start();
	$authurl = "http://$ipaddress:8088/asterisk/rawman?action=login&username=$asteriskusername&secret=$asteriskpassword";
	$auth = curlauth($authurl);

	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_URL, "https://$ipaddress/asterisk/rawman?action=status");
	curl_setopt($ch1, CURLOPT_HEADER, 1);
	curl_setopt ($ch1, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch1, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
	$curlOutput_status = curl_exec($ch1);
	$arr = explode("CallerID: ",$curlOutput_status);
	if(strstr($curlOutput_status,"Response: Success") && strstr($curlOutput_status,"Message: Channel status will follow") /*&& count($arr)>=3*/ )
	{
		$j=0;
		for ($i=1;$i<count($arr);$i++)
		{
			$arrnew[$j] = substr($arr[$i],0,4);
			$j++;
		}
		$arrnew = array_unique($arrnew);
		$phn = $_REQUEST['phn'];
		echo "
			<table>
			<tr>
			<td>
				<label class=\"sublinks\">Extension to snoop</label>
			</td>
			<td>
				<select name='txtsto' style='width:170px;' class='NormalTextBox' id='txtsto'>
					<option value='0'>Select Agent to Snoop</option>
		     ";
			for($i=0;$i<count($arrnew);$i++)
			{
					if($_REQUEST['txtsto'] == $arrnew[$i])
						echo "<option value='$arrnew[$i]' selected>$arrnew[$i]</option>";
					else
						echo "<option value='$arrnew[$i]' selected>$arrnew[$i]</option>";
			}
			echo "
				</select>
			</td>
			<td>";
	       	echo "<INPUT type=\"submit\" value=\"Snoop\" name=\"call\" class=\"NormalButton\" />";
	       	echo "</td>
			</tr>
			</table>
	
		    ";

	}
	else
	{
		echo "<table><tr><td>There are no active channels to snoop</td></tr></table>";
	}
	?>
