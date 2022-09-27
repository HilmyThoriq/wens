<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/head.php";
$query = "UPDATE koweb_mail SET read_state ='Y' WHERE code='$code'";
$result = mysql_query($query);

header("Content-type: image/jpeg");
header('Location: /ko_admin/program/koweb_mail/logoImage.jpeg');
?>