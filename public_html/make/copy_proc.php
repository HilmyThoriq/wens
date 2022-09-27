<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/head.php"; 
include_once $_SERVER['DOCUMENT_ROOT'] . "/inc/header.html"; 

 if($_SERVER['REMOTE_ADDR'] != "106.242.31.74"){
 //	error("비인가 접속");
 //	exit;
 }
############################# 타켓 robots.txt 체크 ###############################
$target_domain = $target_user . ".koweb.co.kr";

$lp = file_get_contents("http://".$target_domain."/robots.txt");
#robots.txt 가 있을경우 접근 X


//기본 퍼미션 701 확인, public_html은 755

if($lp){
	echo "http://".$target_domain."/robots.txt";
	alert("이미 서버에 데이터가 존재합니다.");
	exit;
}

############################# 각서버 접속 ###############################

#베이스 SSL 로그인
$conn = ssh2_connect('210.127.208.136', 7722);
$conn_result = ssh2_auth_password($conn, $host_id, $host_password);

#베이스 접속 체크
if(!$conn_result){
	error("베이스 호스트에 접근하지 못했습니다. 접속정보를 확인하세요");
	exit;
}
#베이스 기본 디렉토리설정
$base_dir = "/home/".$host_id."/public_html/make";


#타겟 SSL 로그인
$conn2 = ssh2_connect('210.127.208.136', 7722);
$conn_result2 = ssh2_auth_password($conn2, $target_host, $target_host_password);

#베이스 접속 체크
if(!$conn_result2){
	error("타켓 호스트에 접근하지 못했습니다. 접속정보를 확인하세요");
	exit;
}
#타겟 디렉토리 설정
$target_base_dir = "/home/".$target_host."/public_html";
$target_base_tmp = "/home/".$target_host."/";

#설치폴더 생성 
ssh2_exec($conn2, "mkdir -m 777 /home/".$target_host."/public_html/setup", 777);

############################# 서버 접속 완료 ###############################


#현재 베이스 db dump
exec("mysqldump -u".$db_user." -p'".$db_pass."' --single-transaction --skip-add-locks ".$db_name." >/home/".$host_id."/public_html/make/".$target_db.".sql");

//echo "mysqldump -u".$db_user." -p'".$db_pass."' --single-transaction --skip-add-locks ".$db_name." >/home/".$host_id."/public_html/make/".$target_db.".sql";

sleep(2);


############################# DB connect 정보파일 생성 ###############################

#db.php 파일 초기화
unlink($_SERVER['DOCUMENT_ROOT']."/make/default_db/db.php");
copy($_SERVER['DOCUMENT_ROOT']."/make/default_db/origin.php", $_SERVER['DOCUMENT_ROOT']."/make/default_db/db.php");
sleep(2);

#db.php 파일을 앞서 입력한 정보로 수정
$filename = $_SERVER['DOCUMENT_ROOT']."/make/default_db/db.php";
$fp = fopen($filename, "r") or die("파일열기에 실패하였습니다");
$buffer = fread($fp, filesize($filename));

$buffer = str_replace("*targetid", $target_user, $buffer);
$buffer = str_replace("*targetpass", $target_pass, $buffer);
$buffer = str_replace("*targetdb", $target_db, $buffer);
fclose($fp);

$f = @fopen($filename,'w');
@fwrite($f,"<?");
@fwrite($f,$buffer);
@fwrite($f,"?>");
@fclose($f);
sleep(2);

############################# DB connect 정보파일 생성끝 ###############################



############################# DB DUMP, TAR 파일생성 ###############################



#public_html 폴더 압축
$tar_command = 'tar -cvf '.$target_host.'.tar -C /home/'.$host_id.'/public_html .';
exec($tar_command);
sleep(2);

############################# DB DUMP, TAR 파일생성 끝 ###############################




############################# SCP SEND  ###############################

#베이스 -> 타겟으로 tar파일 이동
ssh2_scp_send($conn, $base_dir."/".$target_host.".tar", $target_base_dir."/setup/".$target_host.".tar", 0755);

#dbconfig 이동
ssh2_scp_send($conn, $base_dir."/default_db/db.php", $target_base_dir."/setup/dbconfig_.php", 0755);

#sql 덤프 이동
ssh2_scp_send($conn, $base_dir."/".$target_db.".sql", $target_base_dir."/setup/".$target_db.".sql", 0755);
sleep(2);

############################# SCP SEND 끝 ###############################



echo "<br />";
echo "<br />";
echo "<br />";

############################# 보낸파일 삭제  #################################


#tar 파일 삭제
unlink($base_dir."/".$target_host.".tar");

#sql 파일 삭제
unlink($base_dir."/".$target_db.".sql");


############################# 보낸파일 삭제 끝 ###############################



############################# 설치  ###############################

#압축풀기
ssh2_exec($conn2, "tar --keep-old-files -xf ".$target_base_dir."/setup/".$target_host.".tar -C ".$target_base_dir);
sleep(2);

#db접속파일 업데이트
ssh2_exec($conn2, "mkdir -m 755 ".$target_base_tmp."db");
ssh2_exec($conn2, "cp ".$target_base_dir."/setup/dbconfig_.php ".$target_base_tmp."db/db.php");
sleep(2);

#sql 풀기
ssh2_exec($conn2, "mysql -u".$target_user." -p'".$target_pass."' ".$target_db." < " . $target_base_dir."/setup/".$target_db.".sql");

//echo "mysql -u".$target_user." -p'".$target_pass."' ".$target_db." < " . $target_base_dir."/setup/".$target_db.".sql";


sleep(2);

#upload 폴더 권한
ssh2_exec($conn2, "chmod -R 777 ".$target_base_dir."/upload/");
ssh2_exec($conn2, "chmod -R 777 ".$target_base_dir."/ko_finder/userfiles/");

sleep(2);
#설치파일 삭제
ssh2_exec($conn2, "rm -rf ".$target_base_dir."/setup");
ssh2_exec($conn2, "rm -rf ".$target_base_dir."/make");

alert("기본세팅이 완료되었습니다.");
url("/make/copy_form.html");
############################# 설치 끝 ###############################
exit;
?>


