<?php
require_once "maincore.php";
require_once (BASEDIR.STYLE."tpl.start.php");

// 0 - user zalogowany
// 1 - zalogowano poprawnie
// 2 - nie uzupe³niono pól
// 3 - blêdne dane

$result = log_user_in();

if($result == 0) redirect($config['mainpage']);
if($result == 1) redirect($config['mainpage']);
if($result == 2) $msg = "Uzupe³nij wszystkie pola.";
if($result == 3) $msg = "Podano z³± nazwê u¿ytkownika lub has³o.";

banlist_verification();

echo "<div class='not'>\n";
echo $msg."Przejd¼ do <a href='index.php'>strony g³ównej</a>.";
echo "</div>\n";

require_once (BASEDIR.STYLE."tpl.end.php");
?>
