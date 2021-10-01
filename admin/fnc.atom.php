<?php

if( $_GET['option'] == "atom" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	session_check();
	getsettings();
	$title = $config['title'];	$admin = $config['headadmin'];	$email = $config['email'];

	echo "<div class='not'>".atom_convert_news($title,$admin,$email)."</div>\n";
	echo "<div class='not'>".atom_convert_league_games($title,$admin,$email)."</div>\n";
}

?>
