<?php

echo custom_menu();
echo custom_calendar();

if( session_check() != FALSE AND session_check() == TRUE )
{
	echo "      <div class='section-nav'>\n";
	echo "        Zalogowany: <b>".$_SESSION['login']."</b>\n";
	echo "      </div>\n";
	echo "      <div class='section-con'>\n";
	echo "        <ul>\n";
	echo "          <li><a href='profile.php'>Profil</a></li>\n";
	if( session_check_usrlevel() >= SPECIAL_LVL )
	{
	echo "          <li><a href='administration.php'>Panel administracyjny</a></li>\n";
	}
	echo "          <li><a href='logout.php?logout=yes'>Wyloguj</a></li>\n";
	echo "        </ul>\n";
	echo "      </div>\n";
}
elseif ( session_check() == FALSE AND $config['log_sys'] == 1 )
{
	echo "      <div class='section-nav'>\n";
	echo "        Logowanie\n";
	echo "      </div>\n";
	echo "      <div class='section-con'>\n";
	echo "        <form action='login.php' method='post' class='form-login'>\n";
	echo "		  <input type='text' name='login' value='login'/>\n";
	echo "		  <input type='password' name='password' value=''/>\n";
	echo "		  <input type='hidden' name='send' value='login'/>\n";
	echo "		  <input type='submit' class='submit' value='Zaloguj'/>\n";
	echo "		  <p class='text-tiny'>Je¿eli nie masz konta:<br><a href='register.php'>zarejestruj siê</a></p>\n";
	echo "        </form>\n";
	echo "      </div>\n";
}
echo "      <div class='section-nav'>\n";
echo "        On-line\n";
echo "      </div>\n";
echo "      <div class='section-con'>\n";

	$date_online = date("Y-m-d H:i:s",strtotime("-5 minutes"));
	$sql = "SELECT * FROM `".DBPREFIX."users` WHERE `last_date` > '".$date_online."' AND display_online = '1'";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
		echo "        <p>U¿ytkownicy on-line: ";
		$i = 0;
		while($online = mysql_fetch_assoc($query))
		{
	  		if( $i > 0 ) echo ", \n";
	  		$i++;
			if( session_check_usrlevel($online['login']) >= SPECIAL_LVL ) echo "<strong>";
			echo "<a href='#'>".$online['login']."</a>";
			if( session_check_usrlevel($online['login']) >= SPECIAL_LVL ) echo "</strong>";
		}
		echo "\n";
	}
	else echo "        Brak zalogowanych u¿ytkowników online\n";

echo "      </div>\n";
?>
