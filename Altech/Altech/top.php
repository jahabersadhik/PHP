<?php
/*
	//echo "<pre>";
	/*$connection = ssh2_connect('122.165.255.249');
	ssh2_auth_password($connection,'isystem','isystem12345');
	$stream = ssh2_exec($connection, 'df');
	stream_set_blocking($stream, true);
    while($line = fgets($stream)) 
    {
            flush();
            echo $line."<br />";
            
    }
	for($i=0;$i<count($newarr);$i++)
	{
		$retval = "";
		$last_line = exec("df -h $newarr[$i]",$retval);
		print_r($retval);
		echo "<br>";
	}
	$retval = "";
	$last_line = exec("df -h /",$retval);
	for($i=1;$i<count($retval);$i++)
	{
		echo trim($retval[$i]);
		echo "<br>";
	}*/
	/*print_r($value);
	print_r($value_1);*/
?>	
<meta http-equiv="refresh" content="5;" />
<?php
	//Start of Code for Getting CPU usage %
	$last_line = exec('ps -e -o pid,pcpu,user,comm,pmem | grep asterisk',$retval);
	$asterisk = explode(" ",$retval[1]);
	for($i=0,$j=0;$i<count($asterisk);$i++)
	{
		if(trim($asterisk[$i])!='')
		{
			$value[$j] = $asterisk[$i];
			$j++;
		}
	}
	
	$asterisk_safe = explode(" ",$retval[0]);
	for($i=0,$j=0;$i<count($asterisk_safe);$i++)
	{
		if(trim($asterisk_safe[$i])!='')
		{
			$value_1[$j] = $asterisk_safe[$i];
			$j++;
		}
	}
	$retval = "";
	//End of Code for Getting CPU usage %

	//Start of Code for Getting Memory usage
	$last_line = exec("df -h | awk '{print $1} {print $2} {print $3} {print $4} {print $5} {print $6}'",$retval);
	for($i=6,$j=0;$i<count($retval);$i++)
	{
		if(trim($retval[$i])!='')
		{
			$newarr[$j] = $retval[$i];
			$j++;
		}
	}
	//End of Code for Getting Memory usage
	/*echo "<pre>";
	print_r($value);
	print_r($value_1);*/
?>
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<th colspan="6" bgcolor="#fcfcfc">Summary of Disk Space Usage</th>
</tr>
<tr>
	<th width="18%" bgcolor="#fcfcfc">File System</th>
	<th width="18%" bgcolor="#fcfcfc">1K - Blocks</th>
	<th width="18%" bgcolor="#fcfcfc">Used Space</th>
	<th width="18%" bgcolor="#fcfcfc">Available Space</th>
	<th width="10%" bgcolor="#fcfcfc">Usage %</th>
	<th width="18%" bgcolor="#fcfcfc">Mounted On</th>
</tr>
<?php
echo "<tr>";
	for($i=0,$j=1;$i<count($newarr);$i++,$j++)
	{
		echo "<td bgcolor=\"#fcfcfc\">$newarr[$i]</td>";
		if($j%6==0)
			echo "</tr><tr>";
	}
echo "</tr>";
?>
<tr><td colspan="6" height="20" style="border:none;">&nbsp;</td></tr>
</table>

<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<th colspan="5" bgcolor="#fcfcfc">Summary of CPU Usage</th>
</tr>
<tr>
	<th width="20%" bgcolor="#fcfcfc">Process ID</th>
	<th width="20%" bgcolor="#fcfcfc">CPU Usage %</th>
	<th width="20%" bgcolor="#fcfcfc">User</th>
	<th width="20%" bgcolor="#fcfcfc">Command</th>
	<th width="20%" bgcolor="#fcfcfc">RAM Usage %</th>
</tr>
<?php
echo "<tr>";
	for($i=0,$j=1;$i<count($value);$i++,$j++)
	{
		echo "<td bgcolor=\"#fcfcfc\">$value[$i]</td>";
		if($j%5==0)
			echo "</tr><tr>";
	}
echo "</tr>";

echo "<tr>";
	for($i=0,$j=1;$i<count($value_1);$i++,$j++)
	{
		echo "<td bgcolor=\"#fcfcfc\">$value_1[$i]</td>";
		if($j%5==0)
			echo "</tr><tr>";
	}
echo "</tr>";
?>
<tr><td colspan="6" height="20" style="border:none;">&nbsp;</td></tr>
</table>
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$IPAddress/asterisk/rawman?action=login&username=$asteriskUsername&secret=$asteriskPassword");
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$curlOutput = curl_exec($ch);
$expOutput = explode(";",$curlOutput);
$expCookeInfo = explode("mansession_id=",$expOutput[1]);
$cookieValue = str_replace('"','',$expCookeInfo[1]);
setcookie("mansession_id",$cookieValue,time()+3600,"/periisystem/",$_SERVER['SERVER_ADDR']);
curl_close($ch);

$ch = curl_init();
$testurl = "https://$IPAddress/asterisk/rawman?action=command&command=core%20show%20channels";
curl_setopt($ch, CURLOPT_URL, $testurl);
curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$mansession_id = $_COOKIE['mansession_id'];
@curl_setopt($ch, CURLOPT_COOKIE, "mansession_id = ".$cookieValue);
$curlOutput = curl_exec($ch);
curl_close($ch);

$calldetails = explode("(Data)",str_replace("--END COMMAND--","",$curlOutput));
$calls = explode("\n",$calldetails[1]);
//print_r($calls);
for($i=0;$i<count($calls);$i++)
{
	if(stristr($calls[$i],"active channels"))
	{
		$activechannels = $calls[$i];
		$activecalls = $calls[$i+1];
	}
}
if(trim($activecalls) == '')
	$activecalls = "0 active calls";
if(trim($activechannels) == '')
	$activechannels = "0 active channels";
?>
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
	<th colspan="2" bgcolor="#fcfcfc">Active Call Summary</th>
</tr>
<tr>
	<td bgcolor="#fcfcfc" width="50%">Total Number of Active Calls</td>
	<td bgcolor="#fcfcfc" width="50%"><?php echo $activechannels."<br>".$activecalls; ?></td>
</tr>
</table>