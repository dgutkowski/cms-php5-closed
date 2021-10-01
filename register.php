<?php
require_once "maincore.php";
add_title("Rejestracja");
require_once (BASEDIR.STYLE."tpl.start.php");


if( isset($_POST['send']) )
{
	if( $_POST['send'] == "register" )
	{
   	if( !empty($_POST['r1']) AND !empty($_POST['r2']) AND !empty($_POST['r3']) AND !empty($_POST['r4']))
   	{
			if( $_POST['r2'] == $_POST['r3'] )
			{
				if( strlen($_POST['r1']) > 3 )
				{
					if( strlen($_POST['r2']) > 3 )
					{
						$sql = "SELECT * FROM ".DBPREFIX."users WHERE login='".htmlspecialchars($_POST["r1"])."'";
      						$query = mysql_query($sql);
		      				if(mysql_num_rows($query)) echo registration_form("Login jest ju¿ zajêty!","err");
    						else
			      			{
		      	   				$log = htmlspecialchars($_POST["r1"]);
		      	   				$pass = md5(htmlspecialchars($_POST["r2"]));
		      	   				$mail = htmlspecialchars($_POST["r4"]);
		      	   				$clan = htmlspecialchars($_POST["r5"]);
					   			$sql = "INSERT INTO `".DBPREFIX."users` VALUES (NULL, '".$log."', '".$pass."', '".$mail."', '".DATE_SET."', '".USER_IP."', '0000-00-00', '0', '0', '0', '', '', '0', '0', '', '', '', '0000-00-00', '', '', '".$log."', '".$clan."', '', '', '', '0', '0000-00-00','1', '1');";
						    	mysql_query($sql);
							echo registration_form("Rejestracja przebieg³a pomy¶lnie, poczekaj teraz na akceptacjê administracji!","suc");
							
   						}
					}
					else echo registration_form("Has³o powinno sk³adaæ siê z przynajmniej 4 znaków.","not");
				}
				else echo registration_form("Login powinien sk³adaæ siê z przynajmniej 4 znaków.","not");
			}
			else echo registration_form("Podane has³a nie s± identyczne.","not");
		}
		else echo registration_form("Uzupe³nij wymagane pola.","not");
	
	}
	else echo registration_form("test1");
}
else echo registration_form();

require_once (BASEDIR.STYLE."tpl.end.php");
