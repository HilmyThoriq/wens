<?
//ini_set('display_errors', '1'); 
//ini_set('error_reporting', 'E_ALL' );  
@ session_start(); 
@ header('Pragma: no-cache');  
@ header('Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate'); 
@ header('Content-type: text/html; charset=utf-8');  
 
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
$adbgcolor = "#32A49E";
$load = 0 ;


$referer_num = @mysql_result(mysql_query("SELECT COUNT(*) FROM memberLog"), 0, 0);

if ($referer_num > 100000) {
	$refer_sql = mysql_query("SELECT no FROM memberLog ORDER BY no ASC LIMIT 1000");

	while ($refer_row = mysql_fetch_array($refer_sql)) {
		mysql_query("DELETE FROM memberLog WHERE no=$refer_row[no]");
	}
}
?>
