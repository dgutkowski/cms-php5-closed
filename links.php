<?php
require_once("maincore.php");
$actual = "links.php";
add_title("Linki");
require_once(BASEDIR.STYLE."tpl.start.php");

echo "<h2>Linki</h2>\n";

$sql=	"SELECT `id`, `name` FROM ".DBPREFIX."links_cat ORDER BY `order`";
$query   =	mysql_query($sql);
if (mysql_num_rows($query) > 0)
{
	while($row = mysql_fetch_assoc($query))
	{
		$sql2 =	"SELECT `name`, `url`,`cat` FROM ".DBPREFIX."links WHERE `cat` = '".$row['id']."' ORDER BY `order` ASC";
		$query2 = mysql_query($sql2);
		if (mysql_num_rows($query2) > 0)
		{
			echo "<h5>".$row['name']."</h5>\n";
			while($row2 = mysql_fetch_assoc($query2))
			{
				if($row2['cat']==$row['id'])
				{
					echo "<ul>";
					echo "<li><a href='".$row2['url']."'>".$row2['name']."</a>\n";
					echo "<div class='text-tiny'>".$row2['url']."</div>";
					echo "</ul>";
				}
			}
		}
	}
}

require_once(BASEDIR.STYLE."tpl.end.php");
?>
