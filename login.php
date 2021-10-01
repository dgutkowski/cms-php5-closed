<?php
require_once "maincore.php";
require_once (BASEDIR.STYLE."tpl.start.php");

// 0 - user zalogowany
// 1 - zalogowano poprawnie
// 2 - nie uzupe�niono p�l
// 3 - bl�dne dane

$result = log_user_in();

if($result == 0) redirect($config['mainpage']);
if($result == 1) redirect($config['mainpage']);
if($result == 2) $msg = "Uzupe�nij wszystkie pola.";
if($result == 3) $msg = "Podano z�� nazw� u�ytkownika lub has�o.";

banlist_verification();

echo "<div class='not'>\n";
echo $msg."Przejd� do <a href='index.php'>strony g��wnej</a>.";
echo "</div>\n";

require_once (BASEDIR.STYLE."tpl.end.php");
?>
