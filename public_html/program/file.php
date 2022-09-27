<?
 include  "../head.php";

$program_id = strip_tags($program_id);
$file = strip_tags($file);


$type = str_replace("/", "", $type);
$type = str_replace("\\", "", $type);
$type = str_replace("..", "", $type);

$type_id = str_replace("/", "", $type_id);
$type_id = str_replace("\\", "", $type_id);
$type_id = str_replace("..", "", $type_id);

$file = str_replace("/", "", $file);
$file = str_replace("\\", "", $file);
$file = str_replace("..", "", $file);

if (isblank($type))	{
	error("비정상적 접근1");
	exit;
}

if (isblank($type_id))	{
	error("비정상적 접근2");
	exit;
}

if (isblank($file))	{
	error("비정상적 접근3");
	exit;
}

if (!file_exists($_SERVER[DOCUMENT_ROOT] . "/upload/" . $type . "/" . $type_id . "/" . $file)) {
	error("해당 파일이 없습니다.");
	exit;
}

/*----------------------------------------------------------------------------*/
// 정리
/*----------------------------------------------------------------------------*/

if (!ereg("7.0", $HTTP_USER_AGENT)) {
	header("Cache-Control: private, must-revalidate");
}

Header("Content-Disposition: attachment; filename=" . rawurlencode($file));
Header("Content-Type: application/octet-stream");
// Header("Content-Length: " . filesize($_SERVER[DOCUMENT_ROOT] . $ROOT . "/board/data/$program_id/$file"));
Header("Pragma: no-cache");
Header("Expires: 0");

if (is_file($_SERVER[DOCUMENT_ROOT] . "/upload/" . $type . "/" . $type_id . "/" . $file)) {
	$fp = fopen($_SERVER[DOCUMENT_ROOT] . "/upload/" . $type . "/" . $type_id . "/" . $file, "rb");
	if (!fpassthru($fp)) {
		fclose($fp);
	}
}

?>
