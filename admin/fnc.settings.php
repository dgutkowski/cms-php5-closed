<?php

if( $_GET['option'] == "settings" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	
	session_check();
	
	// uptade the settings: meta ; options ; administration
	
	if(@$_POST['send'] == "m")
	{
		$v1 = addslashes($_POST['m1']);
		$v2 = addslashes($_POST['m2']);
		$v3 = addslashes($_POST['m3']);
		$v4 = addslashes($_POST['m4']);
		$v5 = addslashes($_POST['m5']);
		$v6 = addslashes($_POST['m6']);
		$v7 = addslashes($_POST['m7']);
		$sql = "UPDATE `".DBPREFIX."settings` SET `clanname` = '".$v1."', `title` = '".$v2."', `keys` = '".$v3."', `desc` = '".$v4."', `footer` = '".$v5."', `header_text_main` = '".$v6."', `header_text_sub` = '".$v7."' LIMIT 1";
		mysql_query($sql);
		$result = "Zaaktualizowano ustawienia metadane portalu";
 	}
 	if(@$_POST['send'] == "o")
 	{
    		$v1 = addslashes($_POST['o1']);
    		$v2 = addslashes($_POST['o2']);
    		$v3 = addslashes($_POST['o3']);
    		$v4 = $_POST['o4'];
    		$v5 = $_POST['o5'];
    		$v6 = addslashes($_POST['o6']);
    		$v7 = $_POST['o7'];
    		$v8 = $_POST['o8'];
    		$v9 = $_POST['o9'];
    		$v10 = addslashes($_POST['o10']);
    		$sql = "UPDATE `".DBPREFIX."settings` SET `mainpage` = '".$v1."', `logoimage` = '".$v2."', `fav_ico` = '".$v3."', `log_sys` = '".$v4."', `forum_sys` = '".$v5."', `forum_link` = '".$v6."', `banner_sys` = '".$v7."', `antyflood` = '".$v8."', `disabled` = '".$v9."', `disabled_text` = '".$v10."' LIMIT 1";
		mysql_query($sql);
   		$result = "Zaaktualizowano ustawienia ogólne";
	}
 	if(@$_POST['send'] == "a")
	{
		if(!empty($_POST['a1']) AND check_int($_POST['a1']) == TRUE) 
		{
    			$v1 = addslashes($_POST['a1']);
		}
		
		$sql = "SELECT `id` FROM `".DBPREFIX."users` WHERE `id` = '".$v1."' LIMIT 1";
		if(mysql_num_rows(mysql_query($sql)) == 0)
		{
			$result = "<strong>B³±d: </strong> Nie ma takiego u¿ytkownika";
 		}
		else
		{
		   	$v2 = addslashes($_POST['a2']);
		   	$sql = "UPDATE `".DBPREFIX."settings` SET `headadmin` = '".$v1."', `email` = '".$v2."' LIMIT 1";
			mysql_query($sql);
		   	$result = "Zaaktualizowano ustawienia administracji";
   		}
 	}
 	
 	$sql = "SELECT * FROM `".DBPREFIX."settings` LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);

	// fixing vars
	
	$data['clanname'] = stripslashes($data['clanname']);
	$data['title'] = stripslashes($data['title']);
	@$data['keys'] = stripslashes($data['keys']);
	@$data['desc'] = stripslashes($data['desc']);
	$data['footer'] = stripslashes($data['footer']);
	$data['header_text_main'] = stripslashes($data['header_text_main']);
	@$data['header_text_sub'] = stripslashes($data['header_text_sub']);
	$data['mainpage'] = stripslashes($data['mainpage']);
	@$data['logoimage'] = stripslashes($data['logoimage']);
	@$data['favico'] = stripslashes($data['favico']);
	@$data['forum_link'] = stripslashes($data['forum_link']);
	@$data['disabled_text'] = stripslashes($data['disabled_text']);
	
	// set list of admins
	
	$selected = FALSE;
	$disabled = FALSE;
	if( $_SESSION['userid'] != $data['headadmin'] ) $disabled = " disabled='disabled'";
	$list = "<select name='a1' class='w75'".$disabled.">";
	$sql = "SELECT `id`, `login` FROM ".DBPREFIX."users";
	$query = mysql_query($sql);
	while($test = mysql_fetch_assoc($query))
	{
		if( session_check_rights($test['login'],"SA") == TRUE )
		{
			$selected = FALSE;
			if( $test['id'] == $data['headadmin'] ) $selected = " selected='selected'";
			$list.= "<option value='".$test['id']."'".$selected.">".$test['login']."</option>";
		}
	}
	$list.= "</select>";
	
	// fixing: off
	
	echo "<form class='form-style2' action='' method='post'>\n";
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr><th colspan='3'>Ustawienia portalu</th></tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td colspan='3'><h6>Metadane<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></th>";
	echo "</tr>\n";
	echo "<tr><td width='150'>Nazwa klanu</td><td colspan='2'><input class='text' type='text' name='m1' value='".$data['clanname']."'/></td></tr>\n";
	echo "<tr><td width='150'>Tytu³ strony</td><td colspan='2'><input class='text' type='text' name='m2' value='".$data['title']."'/></td></tr>\n";
	echo "<tr><td width='150'>S³owa kluczowe</td><td colspan='2'><input class='text' type='text' name='m3' value='".$data['keys']."'/></td></tr>\n";
	echo "<tr><td width='150'>Opis strony</td><td colspan='2'><input class='text' type='text' name='m4' value='".$data['desc']."'/></td></tr>\n";
	echo "<tr><td width='150'>Stopka</td><td colspan='2'><input class='text' type='text' name='m5' value='".$data['footer']."'/></td></tr>\n";
	echo "<tr><td width='150'>Tekst #1</td><td width='200'><input class='w100' type='text' name='m6' value='".$data['header_text_main']."'/></td><td><span class='text-tiny'>// Tekst g³ówny logo</span></td></tr>\n";
	echo "<tr><td width='150'>Tekst #2</td><td width='200'><input class='w100' type='text' name='m7' value='".$data['header_text_sub']."'/></td><td><span class='text-tiny'>// Tekst wy¶wietlany pod g³ównym napisem na logo</span></td></tr>\n";
	echo "<tr><td width='150'><input type='hidden' name='send' value='m'/></td><td width='200'><input class='submit' type='submit' value='Aktualizuj'/></td><td>&nbsp;</td>\n";	
	echo "</form></tr>\n";
	
	
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td colspan='3'><h6>Ogólnie</h6></th>";
	echo "</tr>\n";
	echo "<tr><td width='150'>Strona g³ówna</td><td width='200'><input class='w100' type='text' name='o1' value='".$data['mainpage']."'/></td><td>&nbsp;</td></tr>\n";
	echo "<tr><td width='150'>Obrazek logo</td><td width='200'><input class='w100' type='text' name='o2' value='".$data['logoimage']."'/></td><td>&nbsp;</td></tr>\n";
	echo "<tr><td width='150'>Ikona strony</td><td width='200'><input class='w100' type='text' name='o3' value='".$data['favico']."'/></td><td>&nbsp;</td></tr>\n";
	echo "<tr><td width='150'>System logowania</td><td width='200'>".form_option_bool($data['log_sys'],"o4","Tak","Nie")."</td><td><span class='text-tiny'>// Je¶li wy³±czone, nie wylogowywuj siê, w przeciwnym wypadku stracisz mo¿liwo¶c zalogowania siê</span></td></tr>\n";
	echo "<tr><td width='150'>System forum</td><td width='200'>".form_option_bool($data['forum_sys'],"o5","Tak","Nie")."</td><td><span class='text-tiny'>// Korzystanie z wbudowanego w portal systemu forum</span></td></tr>\n";
	echo "<tr><td width='150'>Adres forum zewnêtrznego</td><td width='200'><input class='w100' type='text' name='o6' value='".$data['forum_link']."'/></td><td><span class='text-tiny'>// Je¶li wbudowane forum zosta³o wy³±czone</span></td></tr>\n";
	echo "<tr><td width='150'>Bannery</td><td width='200'>".form_option_bool($data['banner_sys'],"o7","Tak","Nie")."</td><td>&nbsp;</td></tr>\n";
	echo "<tr><td width='150'>SPAM Protector</td><td width='200'><input class='w30' type='text' name='o8' value='".$data['antyflood']."'/></td><td><span class='text-tiny'>// Odstêp w sekundach pomiêdzy komentarzami jednego u¿ytkownika</span></td></tr>\n";
	echo "<tr><td width='150'>Wy³±cz system</td><td width='200'>".form_option_bool($data['disabled'],"o9","Tak","Nie")."</td><td>&nbsp;</td></tr>\n";
	echo "<tr><td width='150'>Tekst</td><td width='200'><input class='w100' type='text' name='o10' value='".$data['disabled_text']."'/></td><td><span class='text-tiny'>// Je¶li wbudowane system wy³±czony</span></td></tr>\n";
	echo "<tr><td width='150'><input type='hidden' name='send' value='o'/></td><td width='200'><input class='submit' type='submit' value='Aktualizuj'/></td><td>&nbsp;</td>\n";	
	echo "</form></tr>\n";
	
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td colspan='3'><h6>Administracja</h6></th>";
	echo "</tr>\n";
	if( $_SESSION['userid'] != $data['headadmin'] ) $disable = "disabled='disabled' ";
	echo "<tr><td width='150'>G³ówny administrator</td><td colspan='2'>".$list."</td></tr>\n";
	echo "<tr><td width='150'>Adres e-Mail dla strony</td><td colspan='2'><input class='w75' type='text' name='a2' value='".$data['email']."'/></td></tr>\n";
	echo "<tr><td width='150'><input type='hidden' name='send' value='a'/></td><td colspan='2'><input class='submit' type='submit' value='Aktualizuj'/></td>\n";
	echo "</form></tr>\n";
	echo "</table>\n";	
}
