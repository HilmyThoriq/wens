<? include  "../head.php"; ?>
<?
$total = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_member WHERE id='$variable_data'"));

if(!$total[0]){
	echo "This ID is available.";
} else {
	echo "This ID is unavailable.";
}
?>
