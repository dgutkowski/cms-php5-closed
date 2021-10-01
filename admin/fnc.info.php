<?php

if( $_GET['option'] == "info" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	$sql1 = "SELECT * FROM ".DBPREFIX."users";
	$sql2 = "SELECT * FROM ".DBPREFIX."reg_countries";

	$query1 = mysql_query($sql1);
	$query2 = mysql_query($sql2);
	
  	if( session_check_rights($_SESSION['login'], "SA") == TRUE )
  	{
		echo "  <table class='table0 w100'>\n";
		echo "    <tr><th colspan='6'>Informacje</th></tr>\n";
		echo "    <tr><td colspan='6'><h6>Baza danych<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
		echo "    <tr><td>Host</td><td colspan='5'>".$dbhost."</td></tr>\n";
		echo "    <tr><td>User</td><td colspan='5'>".$dbuser."</td></tr>\n";
		echo "    <tr><td>Name</td><td colspan='5'>".$dbname."</td></tr>\n";
		echo "    <tr><td>PHP v.</td><td colspan='5'>".phpversion()."</td></tr>\n";
  	}
	if( mysql_num_rows($query1)>0 )
	{
		echo "    <tr><td width='170'><h6>Zarejestrowano</h6></td><td width='80'><h6>Jêzyk</h6></td><td width='50'><h6>Kod</h6></td><td width='50' class='center'><h6>Flaga</h6></td><td><h6>Ustawiony</h6></td><td><h6>Znakowanie</h6></td></tr>\n";
		while( $lang = mysql_fetch_assoc($query2) )
		{
			echo "    <tr>";
			echo "<td>".$lang['name']."</td><td>".$lang['lang']."</td><td>".$lang['code']."</td><td class='center'><img src='".IMAGES."flags/small_".$lang['code'].".png'/></td><td>";
			if($lang['set']==0)echo "Istnieje";
			if($lang['set']==1)echo "T³umaczenie";
			if($lang['set']==9)echo "G³ówny";
			echo "</td><td>".$lang['charset']."</td></tr>\n";
		}
	}
	echo "    <tr><td><h6>Typ</h6></td><td colspan='5'><h6>Zajmowane miejsce</h6></td></tr>\n";
	echo "    <tr><td>DIR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *emoty</td><td colspan='5'>".dirsize("images/emots/")."</td></tr>\n";
	echo "    <tr><td>DIR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *avatary</td><td colspan='5'>".dirsize("images/avatars/")."</td></tr>\n";
	echo "    <tr><td>DIR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *pliki</td><td colspan='5'>".dirsize("files/")."</td></tr>\n";
	echo "    <tr><td>DIR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *modu³y</td><td colspan='5'>".dirsize("modules/")."</td></tr>\n";
	echo "  </table>\n";
}

?>
