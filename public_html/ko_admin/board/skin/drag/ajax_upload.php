<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/head.php";
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/board_dragtest/";
print_r($_FILES["file"]);
upload_file($dir, $_FILES["file"][tmp_name], $_FILES["file"][name]);
?>