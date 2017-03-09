<?php
	$foldersArr = scandir("/var/spool/asterisk/voicemail/default/") or die('error');
	for($i=2,$j=0;$i<count($foldersArr);$i++)
	{
		if(is_dir("/var/spool/asterisk/voicemail/default/".$foldersArr[$i]))
		{
			$arr[$j] = $foldersArr[$i];
			$j++;
		}
	}
?>
<form method="POST" action="home.php?page=vm">
<table width="100%" cellpadding="4" cellspacing="4">
<tr>
	<td width="150">Select Extension</td>
	<td width="150">
		<select name="exten" id="exten">
			<option value="-1">Select Extn</option>
			<?php
				for($i=0;$i<count($arr);$i++)
				{
					if($_POST['exten'] == $arr[$i] || $_REQUEST['number'] == $arr[$i])
						echo "<option value='$arr[$i]' selected>$arr[$i]</option>";
					else
						echo "<option value='$arr[$i]'>$arr[$i]</option>";
				}
			?>
		</select>
	</td>
	<td width="500"><input type="submit" name="submit" value="Get Voice Mails"></td>
</tr>
</table>
</form>

<?php
	if($_POST['exten'] || $_REQUEST['number']!='')
	{
		echo "<table width='80%' cellpadding='2' cellspacing='2'>";
		for($i=0;$i<count($arr);$i++)
		{
			if($_POST['exten'] == $arr[$i] || $_REQUEST['number'] == $arr[$i])
			{
				$filesArr = scandir("/var/spool/asterisk/voicemail/default/$arr[$i]/INBOX/");
				if(count($filesArr)>2)
				{
					echo "<tr><td bgcolor='#fcfcfc' colspan='3'>Voice Mails Of $arr[$i]</td></tr>";
					for($j=0/*,$k=0*/;$j<count($filesArr);$j++)
					{
						/*if($k%3 == 0 && $k!=0)
							echo "</tr><tr>";	*/
						echo "<tr>";
						if(stristr($filesArr[$j],'.txt'))
						{
							$str ="";
							$string="";
							$handle = fopen("/var/spool/asterisk/voicemail/default/$arr[$i]/INBOX/$filesArr[$j]",r);
							while($c = fgets($handle))
								$string .= $c;
							fclose($handle);
							$str = date('Ymd-Gis',substr($string,stripos($string,"origtime=")+strlen("origtime="),(stripos($string,"category=")-strlen("category="))-(stripos($string,"origtime="))));
							echo "<td>$str.gsm</td>";
							echo "<td><a href='playFile.php?filename=$filesArr[$j]&mode=v&number=$arr[$i]&height=65&width=330' class='thickbox'>Play File</a></td>";
							echo "<td><a href='playFile.php?filename=$filesArr[$j]&mode=d&number=$arr[$i]'>Delete</a></td>";
						}
						echo "</tr>";
					}
					echo "</tr>";
				}
			}
		}
		echo "</table>";
	}
	else 
	{
		echo "<center>Please Select an Extension to View & Play the Voice Mail</center>";
	}
?>
