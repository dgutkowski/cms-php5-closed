<?php

if( $_GET['option'] == "chat" AND session_check_usrlevel() >= SPECIAL_LVL )
{	
	session_check();
	
	if(@$_POST['send'] == "chat_set" AND !empty($_POST['form2']) AND check_int($_POST['form2']) == TRUE AND check_int($_POST['form3']) == TRUE )
	{
   		$sql = "UPDATE `".DBPREFIX."shoutbox_set` SET `display` = '".$_POST['form1']."', `rows` = '".$_POST['form2']."', `max_lenght` = '".$_POST['form3']."' LIMIT 1";
	   	mysql_query($sql);
	   	$result = "Zaaktualizowano ustawienia";
 	}
 	
 	$sql = "SELECT * FROM `".DBPREFIX."shoutbox_set` LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	if( mysql_num_rows($query) == 0 ) mysql_query("INSERT INTO ".DBPREFIX."shoutbox_set VALUES ('0','0');");
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	echo "<h2>Zarz±dzanie shoutboxem</h2>\n";
	echo "<form class='form-style2' action='' method='post'>\n";
	echo "<table class='table0'>\n";
	echo "<tr><th colspan='2' align='left'>&nbsp;</th></tr>\n";
	echo "<tr>";
	echo "<td width='200'>Poka¿</td><td>".form_option_bool($data['display'],"form1","Poka¿","Wy³±cz")."</td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td width='200'>Liczba wiadomo¶ci</td><td><input class='w30' type='text' name='form2' value='".$data['rows']."'/> <span class='text-tiny'>Ilo¶æ wy¶wietlanych wiadomo¶ci w shoutboxie</span></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td width='200'>Liczba znaków / wiadomo¶æ</td><td><input class='w30' type='text' name='form3' value='".$data['max_lenght']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>";
	echo "<input type='hidden' name='send' value='chat_set'/></td><td><input class='submit' type='submit' value='Wy¶lij'/></td>";
	echo "</tr>\n";
	echo "</table>\n";	
	echo "</form>\n";
}

?>
