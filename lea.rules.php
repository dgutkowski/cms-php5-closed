<?php
require_once("maincore.php");
require_once(BASEDIR.STYLE."tpl.start.php");

if( session_check() == TRUE )
{
	echo "<h2>Regulamin Ligi 0</h2>\n";
	
	echo "<h4 class='center'>&#167; 1</h4><p>Gracze si� zapisuj� przed sezonem</p>\n";
	echo "<h4 class='center'>&#167; 2</h4><p>Po wystartowaniu sezonu nie przyjmujemy zapis�w</p>\n";
	echo "<h4 class='center'>&#167; 3</h4><p>Na start ustawiane s� wyzwania ka�dy z ka�dym, z tym �e s� one nieaktywne</p>\n";
	echo "<h4 class='center'>&#167; 4</h4><p>Aby uaktywni� jakie� wyzwanie trzeba po prostu je odpowiednio klikn�� w systemie, co sprawi �e zostanie ono \"skierowane\" do pe�nego uaktywnienia, pe�ne uaktywnienie jest kwesti� gracza drugiego - w ci�gu 2 tygodni - je�eli tak si� nie stanie, zostanie ono aktywowane automatycznie</p>\n";
	echo "<h4 class='center'>&#167; 5</h4><p>Czas trwania jednego wyzwania w pe�ni uaktywionego to 10dni (liczba do ustalenia)</p>\n";
	echo "<h4 class='center'>&#167; 6</h4><p>W ci�gu trwania sezonu jeden gracz mo�e zosta� wyzwany \"n\" razy naraz, i pomi�dzy jego w�asnymi wyzwaniami musi trwa� \"x\" przerwa</p>\n";
}
require_once(BASEDIR.STYLE."tpl.end.php");
?>
