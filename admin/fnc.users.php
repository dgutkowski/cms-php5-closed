<?php
if( $_GET['option'] == "users" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	session_check();
	
	if( isset($_POST['send']) )
	{	
 		if ( @$_POST['id'] != $config['headadmin'] OR $_SESSION['userid'] == $config['headadmin'] )
 		{
			// update 1st section
			
	 		if( @$_POST['send'] == "upt-i" )
	 		{
				$sql_test = "SELECT `id` FROM ".DBPREFIX."users WHERE `login` = '".$_POST['form1']."' AND `id` != '".$_POST['id']."'";
	 			if( mysql_num_rows(mysql_query($sql_test)) == TRUE ) $test[0] = FALSE; else $test[0] = TRUE;
		 		if( $_POST['form2'] != $_POST['form3'] ) $test[1] = FALSE; else $test[1] = TRUE;
		 		$sql_test = "SELECT `id` FROM ".DBPREFIX."users WHERE `mail` = '".$_POST['form4']."' AND `id` != '".$_POST['id']."'";
		 		if( mysql_num_rows(mysql_query($sql_test)) == TRUE ) $test[2] = FALSE; else $test[2] = TRUE;
				
				if( $test[0] == TRUE AND $test[1] == TRUE AND $test[2] == TRUE AND check_int($_POST['id']) == TRUE )
				{
					$upt['login'] = htmlspecialchars($_POST['form1']);
			   		$upt['mail'] = htmlspecialchars($_POST['form4']);
					$sql = "UPDATE `".DBPREFIX."users` SET `login` = '".$upt['login']."', `mail` = '".$upt['mail']."' WHERE `id` = ".$_POST['id']." LIMIT 1";
					if( $_SESSION['login'] == $_POST['old_login'] ) $_SESSION['login'] = $upt['login'];
					mysql_query($sql);
					$result = "Dane poza has³em zosta³y zaaktualizowane.";
					
					if( strlen($_POST['form2']) > 3 )
					{
						$upt['pass'] = md5(htmlspecialchars(addslashes($_POST['form2'])));
						$sql = "UPDATE `".DBPREFIX."users` SET `password` = '".md5(htmlspecialchars(addslashes($_POST['form2'])))."' WHERE `id` = ".$_POST['id']." LIMIT 1";
						mysql_query($sql);
						$result = "Dane z has³em zosta³y zaaktualizowane.";
					}
					elseif( strlen($_POST['form2']) > 0) $result = "Dane poza has³em zosta³y zmienione.<br>Has³o powinno zawieraæ conajmniej 4 znaki!";
				}
				else $result = "B³±d, dane nie zosta³y zmienione.";
			}
			
	 		// update 2nd section
	 		
	 		if( @$_POST['send'] == 'upt-r' )
	 		{
				if( check_int($_POST['form5']) == TRUE ) $upt['gg'] = $_POST['form5']; else $upt['gg'] = 0;
				if( check_int($_POST['form6']) == TRUE ) $upt['icq'] = $_POST['form6']; else $upt['icq'] = 0;
				@$upt['gamenick'] = htmlspecialchars($_POST['form7a']);
				@$upt['clan'] = htmlspecialchars($_POST['form7']);
				@$upt['frules'] = htmlspecialchars($_POST['form8']);
				@$upt['utitle'] = htmlspecialchars($_POST['form9']);
				@$upt['desc'] = addslashes($_POST['form9b']);
				@$upt['name'] = htmlspecialchars($_POST['form10']);
				@$upt['gender'] = $_POST['form11'];
				@$upt['intrest'] = htmlspecialchars($_POST['form12']);
				@$upt['location'] = htmlspecialchars($_POST['form13']);
				@$upt['country'] = db_get_data("code","reg_countries","name",$_POST['form14']) ;
				@$upt['born'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds'];
				
				if( TRUE == TRUE )
				{
					$sql = "UPDATE `".DBPREFIX."users` SET 
						`gg` = '".@$upt['gg']."', `icq` = '".@$upt['icq']."', 
						`gamenick` = '".@$upt['gamenick']."', `clan` = '".@$upt['clan']."', 
						`favrules` = '".@$upt['frules']."', `usertitle` = '".@$upt['utitle']."', 
						`desc` = '".@$upt['desc']."', 
						`name` = '".@$upt['name']."', `gender` = '".@$upt['gender']."', 
						`intrest` = '".@$upt['intrest']."', `location` = '".@$upt['location']."', 
						`country` = '".@$upt['country']."', `born` = '".@$upt['born']."', 
						`gamenick` = '".@$upt['gamenick']."' 
						WHERE `id` = '".$_POST['id']."' LIMIT 1";
					mysql_query($sql);
					$result = "Dane zosta³y zaaktualizowane.";
				}
			}
			// update 3rd section
	 		
			if( @$_POST['send'] == 'upt-s' )
			{
				$upt['active'] = $_POST['form16'];
				if( check_int($_POST['form17']) == TRUE AND $_POST['form17'] >= 0 AND $_POST['form17'] <= 200 ) $upt['level'] = $_POST['form17'];
				$upt['rights'] = htmlspecialchars($_POST['form18']);
				$sql = "UPDATE `".DBPREFIX."users` SET `active` = '".$upt['active']."', `level` = '".$_POST['form17']."', `rights`='".$upt['rights']."' WHERE `id` = '".$_POST['id']."' LIMIT 1";
				if( $_SESSION['userid'] == $config['headadmin'] AND $_SESSION['userid'] == $_POST['id'] )
				{
					if( $upt['active'] == 1 AND $upt['rights'] == "M.A.SA" AND $upt['level'] >= SPECIAL_LVL )
					{
						mysql_query($sql);
						$result = "Dane zosta³y zaaktualizowane";
					}
					else $result = "Jeste¶ g³ównym admistratorem, nie mo¿esz zmniejszyæ sobie praw ni¿szego rzêdu!";
				}
				else
				{
					mysql_query($sql);
					$result = "Dane zosta³y zaaktualizowane";
				}
				session_check();
			}
		}
		else $result = "Nie masz odpowiednich uprawnieñ aby ingerowaæ w konto g³ównego administratora!";
	}
	
	if( session_check() == TRUE )
	{
		if( !isset($_POST['send']) )
		{
			echo "<div align='center'>\n";
			echo "<table class='table0 w75 form-style1'>\n";
			echo "<tr><th colspan='2'>Operacje na u¿ytkownikach</th></tr>\n";
			echo "<tr>";
			echo "<form action='' method='post'>\n";
			echo "<td><input class='w100' type='text' name='user_login' value=''/></td>";
			echo "<td class='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
			echo "</form>\n";
			echo "</tr>\n";
			echo "</table>\n\n";
			echo "</div>\n";
		}
		if( isset($_POST['send']) OR isset($_POST['user_login']) )
		{
		
			if(!empty($result))
			{
				echo "<div class='suc'>".$result."</div>\n";
			}
			if(isset($_POST['user_login'])) $sql = "SELECT * FROM `".DBPREFIX."users` WHERE `login` = '".htmlspecialchars($_POST['user_login'])."' LIMIT 1";
			else $sql = "SELECT * FROM `".DBPREFIX."users` WHERE `id` = '".$_POST['id']."' LIMIT 1";
			$query = mysql_query($sql);
	   		if( mysql_num_rows($query) == TRUE )
	   		{
	 			$data = mysql_fetch_assoc($query);
	 			$data['desc'] = stripslashes($data['desc']);
	 			$data['country'] = db_get_data("name","reg_countries","code",$data['country']);
	   	   		$ys = substr($data['born'],0,4); $ms = substr($data['born'],5,2);
  				$ds = substr($data['born'],8,2);
	 			
				echo "<table class='table0 w100 form-style1'>\n";
				echo "<tr><th colspan='2'>Operacje na u¿ytkowniku</th></tr>\n";
				echo "<tr><td colspan='2'><h6>u¿ytkownik: ".$data['login']."<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
				echo "<tr><td colspan='2'><h6>Ostatnia aktywno¶æ</h6></td></tr>\n";
				echo "<tr><td width='200'>Data</td><td>".$data['last_date']."</td></tr>\n";
				echo "<tr><td width='200'>IP rejestracji</td><td>".$data['register_ip']."</td></tr>\n";
				echo "<tr><td width='200'>IP ostatnio</td><td>".$data['last_ip']."</td></tr>\n";

				echo "  <tr> <!-- Dane rejestracji --> <td colspan='2'><h6>Dane rejestracji</h6></td></tr>\n";
				echo "  <tr><form action='' method='post'>\n";
				echo "  <td width='200'>Login</td><td><input type='hidden' name='old_login' value='".$data['login']."'/><input class='text' type='text' name='form1' value='".$data['login']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Has³o</td><td><input class='text' type='password' name='form2' value=''/> <span class='text-tiny'>Pozostaw puste</span></td></tr>\n";
				echo "  <tr><td width='200'>Potwierd¼ has³o</td><td><input class='text' type='password' name='form3' value=''/> <span class='text-tiny'>aby nie zmieniaæ</span></td></tr>\n";
				echo "  <tr><td width='200'>e-Mail</td><td><input class='text' type='text' name='form4' value='".$data['mail']."'/></td></tr>\n";
				echo "  <tr><td colspan='2' align='center'>\n  <input type='hidden' name='id' value='".$data['id']."'/>\n  <input type='hidden' name='send' value='upt-i'/>\n  <input class='submit' type='submit' value='Wy¶lij'/>\n  </td>\n";
				echo "  </form></tr>\n";
				
				echo "  <tr> <!-- Dane kontaktowe --> <td colspan='2'><h6>Dane kontaktowe</h6></td></tr>\n";
				echo "  <tr><form class='form-style2' action='' method='post'>\n";
				echo "  <td width='200'>GG:</td><td><input class='w50' type='text' name='form5' value='".$data['gg']."'/></td></tr>\n";
				echo "  <tr><td width='200'>ICQ:</td><td><input class='w50' type='text' name='form6' value='".$data['icq']."'/></td></tr>\n";
				echo "  <tr> <!-- Dane o graczu --> <td colspan='2'><h6>Dane dotycz±ce gracza</h6></td></tr>\n";
				echo "  <tr><td width='200'>Nick:</td><td><input class='text' type='text' name='form7a' value='".$data['gamenick']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Klan:</td><td><input class='p50' type='text' name='form7' value='".$data['clan']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Ulubione rules:</td><td><input class='text' type='text' name='form8' value='".$data['favrules']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Tytu³:</td><td><input class='text' type='text' name='form9' value='".$data['usertitle']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Opis:</td><td><textarea class='textarea' name='form9b'>".@$data['desc']."</textarea></td></tr>\n";
				echo "  <tr> <!-- Dane personalne --> <td colspan='2'><h6>Dane personalne</h6></td></tr>\n";
				echo "  <tr><td width='200'>Imiê:</td><td><input class='text' type='text' name='form10' value='".$data['name']."'/></td></tr>\n";
				echo "  <tr><td width='200'>P³eæ:</td><td>".form_option_bool($data['gender'],"form11","Kobieta","Mê¿czyzna")."</td></tr>\n";
				echo "  <tr><td width='200'>Zainteresowania:</td><td><input class='text' type='text' name='form12' value='".$data['intrest']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Miejscowo¶æ:</td><td><input class='text' type='text' name='form13' value='".$data['location']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Kraj:</td><td><input class='text' type='text' name='form14' value='".$data['country']."'/></td></tr>\n";
				echo "  <tr><td width='200'>Data urodzin:</td><td>";
				echo form_date_born("ys",1940,2040,"Y",60,$ys);
				echo form_date_born("ms",1,12,"m",40,$ms);
				echo form_date_born("ds",1,31,"d",40,$ds);
				echo "  </td></tr>\n";
				echo "  <tr><td colspan='2' align='center'>\n  <input type='hidden' name='id' value='".$data['id']."'/>\n  <input type='hidden' name='send' value='upt-r'/>\n  <input class='submit' type='submit' value='Wy¶lij'/>\n  </td>\n";
				echo "  </form></tr>\n";
				
				echo "  <tr> <!-- Specjalne pola edycji administratora --> <td colspan='2'><h6>Specjalne</h6></td></tr>\n";
				echo "  <tr><form class='form-style2' action='' method='post'>\n";
				echo "  <td width='200'>Aktywny:</td><td>".form_option_bool($data['active'],"form16","Tak","Nie")."</td></tr>\n";
				echo "  <tr><td width='200'>Zezwolenia:</td><td><select style='width:262px;' name='form17'><option value='0'>U¿ytkownik</option><option value='101'"; if( $data['level'] == 101 ) echo " selected='selected'"; echo ">Administrator</option></select></td></tr>\n";
				echo "  <tr><td width='200'>Prawa:</td><td><input class='text' type='text' name='form18' value='".$data['rights']."'/></td></tr>\n";
				echo "  <tr><td colspan='2' align='center'>\n  <input type='hidden' name='id' value='".$data['id']."'/>\n  <input type='hidden' name='send' value='upt-s'/>\n  <input class='submit' type='submit' value='Wy¶lij'/>\n  </td>\n";
				echo "  </form></tr>\n";
				echo "</table>\n";
			}
			else echo "<div class='err'>Brak u¿ytkownika o podanym loginie</div>\n";
		}
	}
}
?>
