<? 
@ session_start(); 
@ header('Pragma: no-cache');  
@ header('Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate'); 
@ header('Content-type: text/html; charset=utf-8');  
 

 if(!defined(__root_pass)){
	define(__root_pass,"true"); 
	$temp_root_pass=realpath(__FILE__);
	if($temp_root_pass) $root_pass=str_ireplace("/head.php","",$temp_root_pass);
	else $root_pass="";
	 
}
include $root_pass."/lib/config.php";
include $root_pass."/lib/function.php";
include $root_pass."/lib/input.php"; 
include $root_pass."/lib/thumbnail.php";

 include $root_pass."/lib/pass.php"; 
 

 
if(!defined(__admin_row)){
	define(__admin_row,"true");
 	$admin_row=dbarray("select * from `daegu_admin`  limit 0,1");
}

 
 
?>