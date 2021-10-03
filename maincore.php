<?php

/*
/* Maincore file
/* Created by Daniel Gutkowski
/* for Polish Revolution clan
/* (c) 2012 All rights reserved
/* Integral part of Polish Revolution's website
/*----------------------------------------------*/

ob_start();
if (preg_match("/maincore.php/i", $_SERVER['PHP_SELF'])) { die(""); }

$level = ""; $i = 0;
while (!file_exists($level."config.php")) {
	$level .= "../"; $i++;
	if ($i == 5) { die("Config file not found"); }
}

require_once $level."config.php";
if (!isset($dbname)) redirect("install.php");

define("BASEDIR", $level);

define("USER_IP", $_SERVER['REMOTE_ADDR']);
define("SCRIPT_SELF", basename($_SERVER['PHP_SELF']));
define("ADMIN", BASEDIR."administration/");
define("IMAGES", BASEDIR."images/");
define("INCLUDES", BASEDIR."includes/");
define("STYLE", "style/");

define("DATE_SET", date("Y-m-d"));
define("DATETIME", date("Y-m-d H:i:s"));
define("TIMESTAMP", time());

define("SPECIAL_LVL", 101);

// PHP 7+ mysql* functions doesn't exists. should use of mysqli

$dbconnect = @mysql_connect($dbhost, $dbuser, $dbpass);
$dbselect = @mysql_select_db($dbname);

if(!$dbconnect) 
	die("<div style='font-size:14px;font-weight:bold;align:center;'>Nie nawi±zano po³±czenia z baz± danych, w razie d³u¿szych problemów, spróbuj skontaktowaæ siê z administratorem</div>");
if(!$dbselect) 
	die("<div style='font-size:14px;font-weight:bold;align:center;'>Nie mo¿na wybraæ potrzebnej bazy danych, w razie d³u¿szych problemów, spróbuj skontaktowaæ siê z administratorem</div>");

$include = glob("includes/inc.*.php");
foreach($include as $file)
{
	if(!is_array($file))
	{
		include_once($file);
	}
}

$module = glob("modules/module.*.php");
foreach($module as $file)
{
	if(!is_array($file))
	{
		include_once($file);
	}
}

function redirect($location, $script = false) {
	if (!$script) 
	{
		header("Location: ".str_replace("&amp;", "&", $location));
		exit;
	} 
	else 
	{
		echo "<script type='text/javascript'>document.location.href='".str_replace("&amp;", "&", $location)."'</script>\n";
		exit;
	}
}

function add_title($value = FALSE)
{
	if($value == FALSE) return FALSE;
	global $config;
	$config['title'].= " - ".$value;
}

function getsettings()
{
	global $config;
	$sql = "SELECT * FROM `".DBPREFIX."settings` LIMIT 1";	
	$query = @mysql_query($sql);
	
	if(@mysql_num_rows($query) > 0)
	{
		$config = @mysql_fetch_assoc($query);
	}
	else
	{
    echo "<div style='font-size:14px;font-weight:bold;align:center;'>Nie znaleziono danych konfiguracyjnych!</div>";
    die();
	}
}

getsettings();

session_name(md5($dbhost.$dbname));
session_start();



function custom_menu()
{
	$sql = "SELECT * FROM ".DBPREFIX."panels WHERE `display` = '1' ORDER BY `order` ASC";
	$query = mysql_query($sql);
	$str = "";
	if( mysql_num_rows($query)>0 )
	{
   	while($panel = mysql_fetch_assoc($query))
   	{
    	$str.= "    <div class='section-nav'>".$panel['name']."</div>\n";
    	$str.= "    <div class='section-con'>\n";
    	$str.= "<!-- ~~~~ -->\n";
    	$str.= stripslashes($panel['content'])."\n";
    	$str.= "    </div>\n";
    	
	}
	return $str;
	}
}

function custom_calendar()
{
	$CountOfDays = array(31,28,31,30,31,30,31,31,30,31,30,31);
	if((date('Y')%4) == 0) $CountOfDays[1] = 29;
	$m['actual'] = date('n')-1;
	$m['name'] = array('Styczeñ','Luty','Marzec','Kwiecieñ','Maj','Czerwiec','Lipiec','Sierpieñ','Wrzesieñ','Pa¼dziernik','Listopad','Grudzieñ');
	$d['actual']['week'] = date('w');
	$d['actual']['month'] = date('j');
	$d['first']['week'] = date('w');
	
	$string = "<!-- Kalendarz -->\n";
	$string.= "    <div class='section-nav'>".$m['name'][$m['actual']]."</div>\n";
	$string.= "    <div class='section-con'>\n";
	$test = date('j');
	while( $test > 7 )
	{ $test = $test - 7; }
	while( $test != 1 )
	{ $d['first']['week'] = $d['first']['week'] - 1;$test = $test-1; if($d['first']['week'] == -1) $d['first']['week'] = 6; }
	$string.= "      <table class='table0 w100 center'>\n";
	$string.= "        <tr><td class='calendar-su'>N</td><td class='calendar-th'>P</td><td class='calendar-th'>W</td>\n        <td class='calendar-th'>¦</td><td class='calendar-th'>C</td><td class='calendar-th'>P</td><td class='calendar-st'>S</td></tr>\n";
	$string.= "        <tr>";
	for( $i = 0;$i < $d['first']['week'];$i++){ $string.= "<td></td>"; }
	$d['printed']['week'] = $d['first']['week'];
	for( $i=1;$i<=$CountOfDays[$m['actual']];$i++ )
	{		
		if( $d['printed']['week'] == 7 ){ $d['printed']['week'] = 0;$string.= "</tr>\n        <tr>"; }
		
		// printed == important dates in database
		
		if($i<10) $j = "0".$i; else $j = $i;
		$sql_date = date("Y")."-".date("m")."-".$j;
		$sql = "SELECT * FROM ".DBPREFIX."calendar WHERE `date` = '".$sql_date."' LIMIT 1";
		if(mysql_num_rows(mysql_query($sql)) AND $i != $d['actual']['month'])
		{
			$calendar = mysql_fetch_assoc(mysql_query($sql));
			$string.= "<td class='calendar-".$calendar['type']."'><a href='"; if($calendar['href']==0)$string.= "#"; $string.= "' title='".$calendar['name']." - ".$calendar['content']."'>".$i."</a></td>"; 
		}
		elseif(mysql_num_rows(mysql_query($sql)) AND $i == $d['actual']['month'])
		{
			$calendar = mysql_fetch_assoc(mysql_query($sql));
			$string.= "<td class='calendar-actual'><a href='"; if($calendar['href']==0)$string.= "#"; $string.= "' title='".$calendar['name']." - ".$calendar['content']."'>".$i."</a></td>"; 
		}
		
		// today == printed
		
		elseif(!mysql_num_rows(mysql_query($sql)) AND $i == $d['actual']['month'] )
		{
			$string.= "<td class='calendar-actual'>".$i."</td>";
		}
		

		
		// else
		
		if(!mysql_num_rows(mysql_query($sql)) AND $i != $d['actual']['month'])
		{
			$string.= "<td>".$i."</td>";
		}
		$d['printed']['week'] = $d['printed']['week']+1;
	}
	for($i=$d['printed']['week'];$i<7;$i++)
	{
		$string.= "<td></td>";
	}
	$string.= "</tr>\n";
	$string.= "      </table>\n";
	$string.= "    </div>\n";
	return $string;
}

function check_int($value)
{
	// custom function about numeric values
	if(is_numeric($value)) return TRUE;
	else return FALSE;
}

function get_gender($bool)
{
	// return a gender from bool value
	if($bool == 0) return "Mê¿czyzna";
	if($bool == 1) return "Kobieta";
}

function get_time_difference($last_date)
{
	// get time difference from unix start
	if($last_date != FALSE)
	{
		$czasostatnio = strtotime($last_date);
		$czasteraz = time();
		$test = $czasteraz-$czasostatnio;
		return $test;
	}
	else return FALSE;
}

// SESSION FUNCTIONS : START

function session_check()
{
	if(isset($_SESSION['logged-in']))
	{
		if($_SESSION['logged-in'] == 1)
		{
			$sql = "SELECT `active` FROM ".DBPREFIX."users WHERE `login` = '".$_SESSION['login']."' AND `active` = '1' LIMIT 1";
			$query = mysql_query($sql);
	   		if(mysql_num_rows($query) > 0)
			{
	  			$sql = "UPDATE `".DBPREFIX."users` SET `last_date` = '".DATETIME."', `last_ip` = '".USER_IP."' WHERE `login` = '".$_SESSION['login']."' LIMIT 1";
	  			mysql_query($sql);
	    			return TRUE;
			}
			else
			{
				$_SESSION['logged-in'] = 0;
				session_unset();
				return FALSE;
			}
		}
		else
		{
			$_SESSION['logged-in'] = 0;
			session_unset();
			return FALSE;
		}
	}
	else return FALSE;
}

function session_check_usrlevel($login = FALSE)
{
	if(!isset($_SESSION['login']) && $login == FALSE) return FALSE;
	if( $login == FALSE ) $sql = "SELECT `level` FROM ".DBPREFIX."users WHERE `login` = '".$_SESSION['login']."' LIMIT 1";
	elseif( !empty($login) ) $sql = "SELECT `level` FROM ".DBPREFIX."users WHERE `login` = '".$login."' LIMIT 1";
	$query = mysql_query($sql);
	if(mysql_num_rows($query) > 0) $test = mysql_fetch_assoc($query);
	else return FALSE;

	return $test['level'];
}

function session_check_rights($login, $right, $check = FALSE)
{
	$sql = "SELECT `rights` FROM `".DBPREFIX."users` WHERE `login` = '".$login."' LIMIT 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$test = explode(".",$data['rights']);
	for( $x = 0; $x < count($test); $x++ ){
		if( $test[$x] == $right ) return TRUE;
	}
	return FALSE;
}

// SESSION FUNCTIONS : END

function banlist_verification()
{

	// temporary ban

	$sql = "SELECT `ban_userid`, `ban_ip`, `ban_reason` FROM `".DBPREFIX."blacklist` WHERE `ban_start` < '".DATETIME."' AND `ban_end` > '".DATETIME."'";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
		while($ban = mysql_fetch_assoc($query))
		{
			if( !empty($ban['ban_reason']) ) $reason = $ban['ban_reason'];
			else $reason = "";
			if( $_SERVER['REMOTE_ADDR'] == $ban['ban_ip'] )
      			{
				die("<div class='err'><img src='images/icons/replies/stop.png' alt=''/><h3>Zosta³e¶ zbanowany. ".$reason."</h3></div>");
				return FALSE;
      			}
      			if( !empty($ban['ban_userid']) )
      			{
      				$sqltext2 = "SELECT `id` FROM ".DBPREFIX."users WHERE `login` = '".@$_SESSION['login']."'";
				$query2 = mysql_query($sqltext2);
				$user = mysql_fetch_assoc($query2);
				
				if( @$_GET['logout'] == "yes" ) return FALSE;
				
				if( $user['id'] == $ban['ban_userid'] )
				{
					die("<div class='err'><img src='images/icons/replies/stop.png' alt=''/><h3>Zosta³e¶ zbanowany. ".$reason."</h3><br/><a href='logout.php?logout=yes'>Wyloguj</a></div>");
					return FALSE;
				}
			}
		}
	}
	
	// permanent ban
	
	$sql = "SELECT `ban_userid`, `ban_ip`, `ban_reason` FROM `".DBPREFIX."blacklist` WHERE `ban_start` < '".DATETIME."' AND `ban_end` = '0'";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
		while($ban = mysql_fetch_row($query))
		{
			if( !empty($ban['ban_reason']) ) $reason = $ban['ban_reason'];
			else $reason = "";
			if( $_SERVER['REMOTE_ADDR'] == $ban['ban_ip'] )
 			{
				die("<div class='err'><img src='images/icons/replies/stop.png' alt=''/><h3>Zosta³e¶ zbanowany. ".$reason."</h3></div>");
				return FALSE;
      			}
      			if( !empty($ban['ban_userid']) )
      			{
      				$sqltext2 = "SELECT `id` FROM ".DBPREFIX."users WHERE `login` = '".@$_SESSION['login']."'";
				$query2 = mysql_query($sqltext2);
				$user = mysql_fetch_assoc($query2);
				
				if( @$_GET['logout'] == "yes" ) return FALSE;
				
				if( $user['id'] == $ban['ban_userid'] )
				{
					die("<div class='err'><img src='images/icons/replies/stop.png' alt=''/><h3>Zosta³e¶ zbanowany. ".$reason."</h3><br/><a href='logout.php?logout=yes'>Wyloguj</a></div>");
					return FALSE;
				}
			}
		}
	}
}

function form_rating()
{
	$return = "<form class='form-style1' action='' method='post'>\n";
	$return.= "<select name='vote' style='width:40px;text-align:center;'>\n";
	$return.= "<option value='5'>5</option><option value='4'>4</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option>\n";
	$return.= "</select>\n";
	$return.= "<input type='hidden' name='send' value='vote'><input type='submit' class='submit' style='width:50px;' value='Oceñ'/>\n";
	$return.= "</form>\n";
	
	return $return;
}

function form_option_bool($value, $name, $type_true, $type_false)
{
	$return = "<input type='radio' name='".$name."' value='1' " ;
	if ($value	== 1) $return .= "checked='checked'";
	$return .= "/> ".$type_true." ";
	$return .= "<input type='radio' name='".$name."' value='0' " ;
	if ($value	== 0)$return .= "checked='checked'";
	$return .= "/> ".$type_false." ";
	return $return;
}

function form_select_date($name,$start,$end,$date,$width,$value = FALSE)
{
	$return = "<select name='".$name."' style='width:".$width.";text-align:center;'>";
	for ( $x = $start;$x <= $end;$x++ )
	{
		if( $date == "m" OR $date == "d" OR $date == "H" OR $date == "i" OR $date == "s" )
		{
			if( $x < 10 )
			$x = "0".$x;
		}
		$return .= "<option value='".$x."' ";
		if(empty($value))
		{
			if($x == date($date))$return .= "selected='selected'";
		}
		elseif($value == $x)	$return .= "selected='selected'";
		$return .= ">".$x."</option>";
	}
	$return .= "</select> ";
	return $return;
}

function form_date_born($name, $start, $end, $date, $width, $value = FALSE)
{
	$return = "<select name='".$name."' style='width:".$width.";text-align:center;'>\n";
	if( $date == "Y") $return .= "<option value='0000'>------</option>\n";
	if( $date == "m" OR $date == "d") $return .= "<option value='00'>---</option>";
	for ( $x = $start;$x <= $end;$x++ )
	{
		if( $date == "m" OR $date == "d" )
		{
			if( $x < 10 )
			$x = "0".$x;
		}
		$return .= "<option value='".$x."' ";
		if(empty($value) AND $x == date($date))
		{
			$return .= "selected='selected'";
		}
		elseif($value == $x)
		{
			$return .= "selected='selected'";
		}
		$return .= ">".$x."</option>";
	}
	$return .= "</select>\n";
	
	return $return;
}

function dirsize($path)
{
	/* http://php.kedziora.info/?id=22 */

	$size = 0;
	
	if($dir = @opendir($path))
	{
		while(($file = readdir($dir)) !== false)
		{
			if($file=='..' OR $file=='.')
			continue;
			elseif( is_dir($path.'/'.$file) )
			$size += dirsize($path.'/'.$file);
			else
			$size += filesize($path.'/'.$file);
		}
		closedir($dir);
	}
	else return ("<div class='err'><b>B£¡D!</b> nie uda³o siê otworzyæ folderu <b>".$path."</b></div>\n");
	$size = round($size/1048576,2);
	return $size." MB";
}

// DATABASE FUNCTIONS AND CUSTOM-PRE-QUERYs

function db_get_data($want,$table,$field,$value,$else=FALSE)
{
	$sql = "SELECT * FROM ".DBPREFIX.$table." WHERE `".$field."` = '".$value."' LIMIT 1";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
   	$result = mysql_fetch_assoc($query);
   	return $result[$want];
	}
	else return $else;
}

// ATOM SYSTEM FUNCTIONS

function atom_url($option = 0)
{
	// get atom url by PHP PRE VARS
	if($option == 0) return "http://".$_SERVER['HTTP_HOST'];
	// get atom id from database
	if($option == 1)
	{
		$sql = "SELECT * FROM ".DBPREFIX."atom WHERE `id` = '1' LIMIT 1";
		$return = mysql_fetch_assoc(mysql_query($sql));
		return $return['atom_url'];
	}
}

function atom_convert_league_games($title, $admin, $email)
{		
	$file_name = "atom-games.xml";
	$i = 0;
	$date = date("Y-m-d\TH:i:s").substr(date("O"),0,3).":".substr(date("O"),3,2);
	$string = "<?xml version='1.0' encoding='iso-8859-2'?>\n";
	$string.= "<feed xmlns='http://www.w3.org/2005/Atom'>\n";
	$string.= "<title>".htmlspecialchars($title)." | Liga 0</title>\n";
	$string.= "<subtitle>Ostatnie gry</subtitle>\n";
	$string.= "<author>\n";
	$string.= "  <name>".htmlspecialchars(db_get_data("login","users","id",$admin))."</name>\n";
	if(db_get_data("login","users","id",$email) != FALSE)
	{
		$string.= "  <email>".htmlspecialchars(db_get_data("login","users","id",$email))."</email>\n";
	}
	$string.= "</author>\n";
	$string.= "<updated>".htmlspecialchars($date)."</updated>\n";
	$string.= "<id>".htmlspecialchars(atom_url())."/lea.games</id>\n";
	$string.= "<link rel='self' href='".htmlspecialchars(atom_url())."/atom-lea.games.xml'/>\n";
	
	$sql = "SELECT * FROM ".DBPREFIX."lea_games WHERE `date` >= '".date("Y-m-d",strtotime("-1 month"))."' ORDER BY `date` DESC";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query) > 0)
	{
		while($game = mysql_fetch_assoc($query))
		{
			if($game['score'] == 1) $score = "2:0";
			if($game['score'] == 2) $score = "2:1";
			if($game['score'] == 3) $score = "WAL";
			
			$string.= "<entry>\n";
			$string.= "<title>".htmlspecialchars(db_get_data("login","users","id",$game['player1']))." vs ".htmlspecialchars(db_get_data("login","users","id",$game['player2']))."</title>\n";
			$string.= "<link rel='alternate' type='text/html' href='".htmlspecialchars(atom_url())."/lea.table.php'/>\n";
			$string.= "<updated>".htmlspecialchars(date("Y-m-d\TH:i:s",strtotime($game['date'])))."</updated>\n";
			$string.= "<author>\n";
			$string.= "  <name>".htmlspecialchars(db_get_data("login","users","id",$game['score_accept'],"?"))."</name>\n";
			$string.= "</author>\n";
			$string.= "<id>".htmlspecialchars(atom_url())."/lea.games-".htmlspecialchars($game['id'])."</id>\n";
			$string.= "<content type='html'>\n";
			$string.= $game['date']." : Winner ".htmlspecialchars(db_get_data("login","users","id",$game['winner']))." by ".$score."\n";
			$string.= "</content>\n";
			$string.= "</entry>\n";
			$i = $i+1;
		}
	}
	$string.= "</feed>\n";
	
	if (is_writable($file_name))
	{
		//	webmade.org
		//	
		//  * "a" - Otwiera plik do zapisu Dane bêd± dodawane na koñcu pliku.
    	//	* "a+" - Otwiera plik do odczytu i zapisu. Dane bêd± dodawane na koñcu pliku.
	    //	* "r" - Otwiera plik tylko do odczytu.
	    //	* "r+" - Otwiera plik do odczytu i zapisu. Dane bêd± dodawane na pocz±tku pliku.
	    //	* "w" - Otwiera plik tylko do zapisu i kasuje poprzedni± zawarto¶æ. Je¶li plik nie istnieje zostanie on stworzony.
	    //	* "w+" - Otwiera plik do zapisu i odczytu. Zawarto¶æ pliku zostaje skasowana. Je¶li plik nie istnieje zostanie on stworzony.
		
		if ($file = fopen($file_name, "w"))
		{
			if(fwrite($file, $string) !== FALSE)
			{
				return "<b>Liga - Gry:</b> - aktualizacja poprawna. Zapisano wiadomo¶ci: ".$i."";
			}
			else return "Zapis do pliku siê nie powiód³";
			fclose($file);
		}
		else return "Nie mogê nawi±zaæ po³±czenia z plikiem";
	}
	else return "Do pliku nie mo¿na dopisaæ informacji lub on nie istnieje";
}

function atom_convert_news($title, $admin, $email)
{		
	$file_name = "atom-news.xml";
	$i = 0;
	// $date = date("Y-m-d\TH:i:s").substr(date("O"),0,3).":".substr(date("O"),3,2);
	$date = date("c");
	$string = "<?xml version='1.0' encoding='iso-8859-2'?>\n";
	$string.= "<feed xmlns='http://www.w3.org/2005/Atom'>\n";
	$string.= "<title>".htmlspecialchars($title)." | Nowo¶ci</title>\n";
	$string.= "<subtitle>Nowe posty</subtitle>\n";
	$string.= "<author>\n";
	$string.= "  <name>".htmlspecialchars(db_get_data("login","users","id",$admin))."</name>\n";
	if(db_get_data("login","users","id",$email) != FALSE)
	{
		$string.= "  <email>".htmlspecialchars(db_get_data("login","users","id",$email))."</email>\n";
	}
	$string.= "</author>\n";
	$string.= "<updated>".htmlspecialchars($date)."</updated>\n";
	$string.= "<id>".htmlspecialchars(atom_url())."/news</id>\n";
	$string.= "<link rel='self' href='".htmlspecialchars(atom_url())."/atom-news.xml'/>\n";
	
	$sql = "SELECT * FROM ".DBPREFIX."news WHERE `date_start` >= '".date("Y-m-d H:i:s",strtotime("-2 months"))."' AND `date_start` <= '".date("Y-m-d H:i:s")."' ORDER BY `date_start` DESC";
	$query = mysql_query($sql);
	
	if(@mysql_num_rows($query) > 0)
	{
		while($news = mysql_fetch_assoc($query))
		{
			$string.= "<entry>\n";
			$string.= "<title>".htmlspecialchars(stripslashes($news['title']))."</title>\n";
			$string.= "<link rel='alternate' type='text/html' href='".htmlspecialchars(atom_url()."/news.php?action=read&id=".$news['id'])."'/>\n";
			$string.= "<updated>".htmlspecialchars(date("c",strtotime($news['date_start'])))."</updated>\n";
			$string.= "<author>\n";
			$string.= "  <name>".htmlspecialchars(db_get_data("login","users","id",$news['author'],"[brak]"))."</name>\n";
			$string.= "</author>\n";
			$string.= "<id>".htmlspecialchars(atom_url())."/news-".htmlspecialchars($news['id'])."</id>\n";
			$string.= "<content type='html'>\n";
			$string.= htmlspecialchars(codes($news['text'].$news['text_ext']))."\n";
			$string.= "</content>\n";
			$string.= "</entry>\n";
			$i = $i+1;
		}
	}
	$string.= "</feed>\n";
	
	if (is_writable($file_name))
	{
		//	webmade.org
		//	
		//  * "a" - Otwiera plik do zapisu Dane bêd± dodawane na koñcu pliku.
    	//	* "a+" - Otwiera plik do odczytu i zapisu. Dane bêd± dodawane na koñcu pliku.
	    //	* "r" - Otwiera plik tylko do odczytu.
	    //	* "r+" - Otwiera plik do odczytu i zapisu. Dane bêd± dodawane na pocz±tku pliku.
	    //	* "w" - Otwiera plik tylko do zapisu i kasuje poprzedni± zawarto¶æ. Je¶li plik nie istnieje zostanie on stworzony.
	    //	* "w+" - Otwiera plik do zapisu i odczytu. Zawarto¶æ pliku zostaje skasowana. Je¶li plik nie istnieje zostanie on stworzony.
		
		if ($file = fopen($file_name, "w"))
		{
			if(fwrite($file, $string) !== FALSE)
			{
				return "<b>Newsy:</b> - aktualizacja poprawna. Zapisano wiadomo¶ci: ".$i."";
			}
			else return "Zapis do pliku siê nie powiód³";
			fclose($file);
		}
		else return "Nie mogê nawi±zaæ po³±czenia z plikiem";
	}
	else return "Do pliku nie mo¿na dopisaæ informacji lub on nie istnieje";
}

function atom_convert_content($string)
{    
	$string = strip_tags(stripslashes($string));
	
	$string = preg_replace("#\[b\](.*?)\[/b\]#si",'\\1',$string);
	$string = preg_replace("#\[i\](.*?)\[/i\]#si",'\\1',$string);  
	$string = preg_replace("#\[u\](.*?)\[/u\]#si",'\\1',$string);
	$string = preg_replace("#\[s\](.*?)\[/s\]#si",'\\1',$string);
	$string = preg_replace("#\[size=small\](.*?)\[/size\]#si",'\\1',$string);
	$string = preg_replace("#\[url\](.*?)\[/url\]#si", "\\1", $string);
	$string = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "\\2", $string);
	$string = preg_replace("#\[img\](.*?)\[/img\]#si",'',$string);
	$string = preg_replace("#\[code\](.*?)\[/code\]#si",'\\1',$string);
	 
	$string = preg_replace("#\[ul\](.*?)\[/ul\]#si",'\\1',$string);
	$string = preg_replace("#\[li\](.*?)\[/li\]#si",'\\1',$string);
	$string = preg_replace("#\[clan\](.*?)\[/clan\]#si"," \\1 ",$string);
	
	$sql = "SELECT * FROM `".DBPREFIX."reg_countries`";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0)
	{
		while($lang = mysql_fetch_assoc($query))
		{
			$string = str_replace("[lang]".$lang['code']."[/lang]","",$string);
		}
	}
	return htmlspecialchars($string);
}

session_check();
banlist_verification();
/*
if($config['disabled'] == TRUE AND session_check_usrlevel() < SPECIAL_LVL)
{
	echo "<div style='font-size:14px;font-weight:bold;align:center;'>".stripslashes($config['disabled_text'])."</div>";
    die();
}
*/
