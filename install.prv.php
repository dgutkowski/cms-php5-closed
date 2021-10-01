<?php
require_once("install.core.php");

$sql = array(
		"INSERT INTO `settings` VALUES('news.php','Nazwa','1','admin@domena.pl','Nazwa','Nazwa','Podtytuł!','PRSystem BETA ; Cossacks BtW is the game by CDV & GSC','','images/favico.ico','desc','keys','','1','0','http://forum.kozacy.org/','0','30','');",
		"INSERT INTO `users` VALUES('','Admin','74b87337454200d4d33f80c4663dc5e5','admin@revolution.pl','2012-04-10','0','0000-00-00 00:00:00','0','1','101','M.A.SA','0','0','','','','1950-01-01','0','POL','Nick','PR','','','1','2005-01-01','1','1');",
		"INSERT INTO `panels` VALUES('','Polska Rewolucja','<ul><li><a href='clan-members.php'>{TEAM}</a></li></ul>','1','1');",
		"INSERT INTO `panels` VALUES('','Liga 0','<ul><li><a href='lea.table.php'>{TABLE}</a></li><a href='lea.table.php'>{STATS}</a></li><a href='lea.account.php'>{SYSTEM}</a></li></ul>','1','2');",
		"INSERT INTO `reg_countries` VALUES('','Polska','Polski','POL','1','ISO-8859-2');",
		"INSERT INTO `reg_countries` VALUES('','Russia','Russian','RUS','0','UTF-8');",
		"INSERT INTO `reg_countries` VALUES('','England','English','ENG','0','ISO-8859-1');"
		);

for($i=0;!empty($sql[$i]);$i++)
{
	echo $sql[$i]."<br>";
	mysql_query($sql[$i]);
	echo "COMPLETED;";
}

?>
