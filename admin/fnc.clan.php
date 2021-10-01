<?php

if( $_GET['option'] == "clan" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	session_check();
	
	if(@$_POST['send']==1)
	{
		$upt['date'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds'];
		$sql = "UPDATE ".DBPREFIX."users SET `siteclan_member` = '".$_POST['set1']."'";
		if( $_POST['set1'] == 0 ) $sql.= ",`siteclan_date` = NULL";
		else $sql.= ",`siteclan_date` = '".$upt['date']."'";
		$sql.= " WHERE `login` = '".$_POST['set0']."' LIMIT 1";
		mysql_query($sql);
		$result = "Zaaktualizowano";
	}
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	
	echo "<table class='table0 w100 form-style1' >\n";
	echo "<tr><th colspan='4'>Zarz±dzanie klanem</th></tr>\n";
	echo "<tr><td colspan='4'><h6><a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
	echo "<tr>\n";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>Login</td><td colspan='3'><input class='w50' type='text' name='set0' value=''/> Od: ";
	echo form_select_date("ys",2000,date("Y"),"Y",60);
	echo form_select_date("ms",1,12,"m",40,1);
	echo form_select_date("ds",1,31,"d",40,1);
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>&nbsp;</td><td colspan='3'>";
	echo form_option_bool(1,"set1","Dodaj","Skasuj");
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>&nbsp;</td><td colspan='3'><input type='hidden' name='send' value='1' /><input class='submit' type='submit' value='OK' /></td>\n";
	echo "</form>\n";
	echo "</tr>\n";
	
	$sql = "SELECT * FROM ".DBPREFIX."users WHERE `siteclan_member` = '1' ORDER BY `login` ASC";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0);
	{
		echo "  <tr><td width='150'><h6>Login</h6></td><td class='center'><h6>W klanie od</h6></td><td><h6 class='center'>Aktywno¶æ</h6></td><td class='center'><h6>on-line</h6></td></tr>\n";
		while($user = mysql_fetch_assoc($query))
		{
			if(get_time_difference($user['last_date'])<2678400) $active = "Aktywny</span>";
			else $active = "<span class='red' title='Ostatnio widziany: ".$user['last_date']."'>Nieaktywny</span>";
			if(get_time_difference($user['last_date'])<300) $online = "<span class='green'>on-line</span>";
			else $online = "";
			$clantime = explode("-",$user['siteclan_date']);
			if ( $clantime[0] == "0000" OR empty($clantime[0]) ) $clantime[0] = "Brak danych";
			
			echo "  <tr><td width='150'>".$user['login']."</td><td class='center'>".$clantime['0']."-".$clantime['1']."-".$clantime['2']."</td><td class='center'>".$active."</td><td class='center'>".$online."</td></tr>\n";
		}
	}
	
	echo "</table>\n"; 	
}

?>
