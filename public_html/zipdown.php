<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

//$tar_command = 'tar -czvf /home/combine/public_html/upload/board_gallery2/test.zip -C /home/combine/public_html/upload/board_gallery2 .';
$tar_command = 'find /home/combine/public_html/upload/board_gallery2 -name "1111.jpg"  -o -name "thumb_1111.jpg" | xargs tar -czvf /home/combine/public_html/upload/board_gallery2/test.zip';
exec($tar_command);
?>
