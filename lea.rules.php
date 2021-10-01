<?php
require_once("maincore.php");
require_once(BASEDIR.STYLE."tpl.start.php");

if( session_check() == TRUE )
{
	echo "<h2>Regulamin Ligi 0</h2>\n";
	
	echo "<h4 class='center'>&#167; 1</h4><p>Gracze siê zapisuj± przed sezonem</p>\n";
	echo "<h4 class='center'>&#167; 2</h4><p>Po wystartowaniu sezonu nie przyjmujemy zapisów</p>\n";
	echo "<h4 class='center'>&#167; 3</h4><p>Na start ustawiane s± wyzwania ka¿dy z ka¿dym, z tym ¿e s± one nieaktywne</p>\n";
	echo "<h4 class='center'>&#167; 4</h4><p>Aby uaktywniæ jakie¶ wyzwanie trzeba po prostu je odpowiednio klikn±æ w systemie, co sprawi ¿e zostanie ono \"skierowane\" do pe³nego uaktywnienia, pe³ne uaktywnienie jest kwesti± gracza drugiego - w ci±gu 2 tygodni - je¿eli tak siê nie stanie, zostanie ono aktywowane automatycznie</p>\n";
	echo "<h4 class='center'>&#167; 5</h4><p>Czas trwania jednego wyzwania w pe³ni uaktywionego to 10dni (liczba do ustalenia)</p>\n";
	echo "<h4 class='center'>&#167; 6</h4><p>W ci±gu trwania sezonu jeden gracz mo¿e zostaæ wyzwany \"n\" razy naraz, i pomiêdzy jego w³asnymi wyzwaniami musi trwaæ \"x\" przerwa</p>\n";
}
require_once(BASEDIR.STYLE."tpl.end.php");
?>
