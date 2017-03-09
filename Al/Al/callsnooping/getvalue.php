<?php
session_start();
include("../connection.php");

function find($start,$end,$string)
{
        $startlength = strpos($string,$start);//+strlen($start);
        $endlength = strpos($string,$end);
        $finallength = $endlength - $startlength;
        $final = substr($string,$startlength,$finallength);
        return $final;
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,$strToAdd,$FSERVER);
curl_close($ch);

$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=status");
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
		$val = find('CallerID:','CallerIDNum:',$arr[$i]);
                $val = str_replace('CallerID:','',trim($val));
                if(strlen($val)==4)
                $arrnew[$j] = $val;
                $j++;
	}
        $phn = $_REQUEST['phn'];
	$arrnew = array_unique($arrnew);
	$arrnew = array_values($arrnew);
	
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
				//if($_REQUEST['txtsto'] == $arrnew[$i])
				//	echo "<option value='$arrnew[$i]' selected>$arrnew[$i]</option>";
				//else
				//	echo "<option value='$arrnew[$i]' selected>$arrnew[$i]</option>";
				if(trim($arrnew[$i])!='')
                        	{

	                                if($_REQUEST['txtsto'] == $arrnew[$i])
        	                                echo "<option value='$arrnew[$i]' selected>$arrnew[$i]</option>";
                	                else
                        	                echo "<option value='$arrnew[$i]' selected>$arrnew[$i]</option>";
                        	}
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
