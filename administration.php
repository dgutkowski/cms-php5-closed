<?php
require_once("maincore.php");
require_once("lea.core.php");
add_title("Administracja");
require_once(BASEDIR.STYLE."tpl.start.php");

function set_admin_cat($category, $need_right = FALSE)
{
	if ( $need_right != FALSE )
	{
		$need_right = explode(".", $need_right);
		for( $x = 0; $x < count($need_right); $x++ )
		{ if( session_check_rights($_SESSION['login'], $need_right[$x]) == TRUE ) return "<tr><td><h6>".$category."</h6></td></tr>\n"; }
	}
}
function set_admin_option($option, $name, $need_right = FALSE)
{
	if ( $need_right != FALSE )
	{
		$need_right = explode(".", $need_right);
		for( $x = 0; $x < count($need_right); $x++ )
		{ if( session_check_rights($_SESSION['login'], $need_right[$x]) == TRUE ) return "<div class='admin-catlist'><a href='administration.php?option=".$option."'><img src='".IMAGES."icons/admin/".$option.".png' border='0'/><div class='clear'></div><span class='text-tiny'>".$name."</span></a></div>\n"; }
	}
	else return "#ERR\n";
}

if( session_check_usrlevel() >= SPECIAL_LVL )
{	
	if( !isset($_GET['option']) )
	{
		echo "<table class='table0 w100'>\n";
		echo "<tr><th>Administracja</th></tr>\n";
		echo set_admin_cat('Zawarto¶æ','M.A.SA');
		echo "<tr><td>\n";
		echo set_admin_option('news','Nowo¶ci','M.A.SA');
		echo set_admin_option('articles','Artyku³y','M.A.SA');
		echo set_admin_option('faq','FAQ','M.A.SA');
		echo set_admin_option('links','Linki','M.A.SA');
		echo set_admin_option('links-cat','Kat. linków','M.A.SA');
		echo set_admin_option('download','Download','A.SA');
		echo set_admin_option('download-cat','Kat. downloadu','A.SA');
		echo set_admin_option('gallery','Galeria','A.SA');
		echo set_admin_option('calendar','Kalendarz','M.A.SA');
		echo set_admin_option('atom','Kana³y ATOM','M.A.SA');
		echo "</td></tr>\n";
		echo set_admin_cat('U¿ytkownicy','A.SA');
		echo "<tr><td>\n";
		echo set_admin_option('users','U¿ytkownicy','A.SA');
		echo set_admin_option('bans','Czarna lista','A.SA');
		echo "</td></tr>\n";
		echo set_admin_cat('Konfiguracja','A.SA');
		echo "<tr><td>\n";
		echo set_admin_option('info','Informacje','A.SA');
		echo set_admin_option('languages','Jêzyki','A.SA');
		echo set_admin_option('clan','Klan','A.SA');
		echo set_admin_option('menu','Menu','A.SA');
		echo set_admin_option('settings','Ustawienia','SA');
		echo "</td></tr>\n";
		echo set_admin_cat('Liga 0','LA.A.SA');
		echo "<tr><td>\n";
		echo set_admin_option('lea.players','Gracze','LA.A.SA');
		echo set_admin_option('lea.games','Gry','LA.A.SA');
		echo set_admin_option('lea.medals','Medale','LA.A.SA');
		echo set_admin_option('lea.config','Konfiguracja','A.SA');
		echo "</td></tr>\n";
		echo "</table>\n";
	}
	elseif( isset($_GET['option']) AND session_check() == TRUE )
	{
		$include = glob("admin/fnc.*.php");
		foreach($include as $file)
		{
			if(!is_array($file)) include_once($file);
		}
	}
}

require_once(BASEDIR.STYLE."tpl.end.php");
?>
