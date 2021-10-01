<?php
require_once("maincore.php");
$actual = "download.php";
add_title("Download");
require_once(BASEDIR.STYLE."tpl.start.php");

echo "<h2>Download</h2>\n";
if( !isset($_GET['id']) )
{
	$sql    =	"SELECT  `id`, `name` FROM ".DBPREFIX."files_cat WHERE `order` > '0' ORDER BY `order`";	
	if(!empty($_GET['cat']) AND check_int($_GET['cat']) == TRUE)
	{ $sql = "SELECT `id`, `name` FROM ".DBPREFIX."files_cat WHERE `id` = '".mysql_real_escape_string($_GET['cat'])."'"; }	
	$query   =	mysql_query($sql);
	if (mysql_num_rows($query) > 0)
	{
	  while($row = mysql_fetch_row($query))
	  {
	    $sql2 =	"SELECT *
	    		FROM ".DBPREFIX."files WHERE `cat` = '".$row[0]."' AND `accepted` = '1' ORDER BY `date` ASC";
	    $query2=mysql_query($sql2);
	    if (mysql_num_rows($query2) > 0)
	    {
	    echo "<h5>".$row[1]."</h5>\n";
	    while($row2 = mysql_fetch_assoc($query2))
	      {
	        echo "<div class='download-name' >".stripslashes($row2['name'])."</div>\n";
	        echo "<div class='download-desc' >".stripslashes($row2['desc'])."</div>\n";
	        echo "<div class='download-down' >".$row2['date']." | ".db_get_data("login","users","id",$row2['adds'],"Brak u¿ytkownika")."<span class='right'>".$row2['size']." KB ";
	  	    if($row2['direct'] == 1) echo "<a href='download.php?id=".$row2['id']."'>DOWNLOAD</a></span>";
  		    elseif($row2['direct'] == 0) echo "<a href='".$row2['url']."'>DOWNLOAD</a></span>";
  		    echo "</div>\n";
	      }
	    }
	  }
	}
}

if( isset($_GET['id']) AND check_int($_GET['id']) == TRUE AND session_check() == TRUE )
{
	$sql = "SELECT `name`, `url`, `id` FROM ".DBPREFIX."files WHERE `id` = '".$_GET['id']."' LIMIT 1";		
	$query = mysql_query($sql);
	if(mysql_num_rows($query) > 0)
	{
	    $file = mysql_fetch_assoc($query);
		echo "<div class='suc'>";
		echo "Pobierasz: <b>".$file['name']."</b>\n";
		echo "<p>Za chwilê powinno ukazaæ siê okno pobierania, je¿eli masz problem, skontaktuj siê z <a href='contact.php'>administratorem</a></p>";
		echo "</div>\n";
		echo "<meta http-equiv='Refresh' content='5; url=downloading.php?id=".$file['id']."' />\n";
	}
}
elseif ( isset($_GET['id']) AND check_int($_GET['id']) == FALSE )
{
	echo "<div class='err'>";
	echo "B³êdny identyfikator pliku, <a href='download.php'>spróbuj ponownie</a>";
	echo "</div>\n";
}
elseif ( isset($_GET['id']) AND session_check() == FALSE )
{
	echo "<div class='not'>";
	echo "Tylko zalogowani u¿ytkownicy mog± pobieraæ pliki";
	echo "</div>\n";
}
require_once(BASEDIR.STYLE."tpl.end.php");
?>
