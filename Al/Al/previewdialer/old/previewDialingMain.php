<?php
$crmData = mysql_query("select *from previewDialing where assignAgent=''");
//$agentData = mysql_query("select *from users where typeOfUser='Call Agent' AND loggedInStatus='loggedIn'");
?>
<form method="post" action="previewdialer/startPreviewDialing.php">
<table width="100%" cellpadding="2" cellspacing="2" align="center">
<tr bgcolor="#ff9900" class="headings">
	
	<th align='left'>Customer Name</th>
	<th align='left'>Phone No</th>
	<th align='left'>Address</th>
</tr>
<?php
	while($crm = mysql_fetch_array($crmData))
	{
		echo "<tr bgcolor='#F2F5F8'>
				
				<td align='left'>".$crm['firstname']." ".$crm['lastname']."</td>
				<td align='left'>".$crm['phone']."</td>
				<td align='left'>".$crm['city']." ".$crm['state']."</td>
			  </tr>	
			 ";
	}
?>
<tr>
	<td colspan="3" height="20"></td>
</tr>
<tr>
	<td colspan="3" height="20" align=center><input type="submit" name="submit" value="Start Preview Dialing" class=NormalButton /></td>
</tr>
</table>
</form>	
</td>
</tr>
</table>
</div>

