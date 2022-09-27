<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Document</title>
 </head>
 <body>
  <?
	$du=`du -sk`;
	$save=100;             //할당받은 계정용량, 단위 MBytes
	$du=$du/1000;
	$result2=$du;
	echo "<font size='2' color='#0078FF'>전체 용량 :<font size='2' color='#ff6666'> 100MB</font><br>";
	echo "<font size='2' color='#0078FF'>사용량 :</font><font size='2' color='#FF6666'> ${result2} Mbyte</font><br>";











  ?>




  <?
		$dir = opendir("/home/");
		while ($folder = readdir($dir)) {
			if ( $folder != "." && $folder != ".." ) {
	?>
			<span><?=$folder?></span><br />
	<?
				$sub_dir = opendir($_SERVER[DOCUMENT_ROOT]."/contents/".$folder."/");
				while($sub_folder = readdir($sub_dir)){
					if ( $sub_folder != "." && $sub_folder != ".." ) {
						$target_file = $add_folder."/contents/".$folder."/" . $sub_folder;
	?>
						<span style="padding-left:40px;"> ㄴ <a href="#" onclick="ajax_load_data('<?=$target_file?>');"><?=$sub_folder?></a> ----- <a href="<?=$target_file?>" target="_blank">[ 새창보기 ]</a></span><br />
	<?
					}
				}
			}
		}
	?>

 </body>
</html>
