<?php
//액세스 토큰 발급
$url = "https://api.instagram.com/oauth/access_token";
$post_array = array(
    'client_id'=>'471312404104868',
    'client_secret'=>'74fbbb0c7b62b286cffe0ffbb120e91a',
    'grant_type'=>'authorization_code',
    'redirect_uri'=>'https://www.naver.com/',
    'code'=>'AQCSJ9gysOgORPFGpP5u7BInrXy3n1-_0yqsMdl1hrUXt2xncsJSngsbapmxbt8Nh4LXwUByVDDY2OY3VE2hW4Z-MHlg43wCH9kbQvul5TSk-UkA2rkoZHR2rfBwkn7VvQ2BxeZmSeCU_9dpVGk44fDRdbreb0akG7U-lvChL7dv_KC1WlJJ1MnZvt5UP1_Wnxdlhkr-wxjJxQtAWG_qGubnW5Z6jmVsUMkBi7DJh4Kgww'
);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST,true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post_array);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($curl);
curl_close($curl);
$result = json_decode($result,true);
print_r($result);
?>


