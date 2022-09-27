<?

/////  / / // / / a/d/vㅍㅍㅍ
function bytexor($a,$b,$ilimit) 
{ 
        $c=""; 
        for($i=0;$i<$ilimit;$i++) { 
            $c .= $a{$i}^$b{$i}; 
        } 
        return $c; 
} 


function decrypt_md5($msg,$key) 
{ 

	if ($msg=='' ) return '' ; 
	
 
	$key  = md5($key); 
		$msg =  base64_decode($msg) ;
        $string=""; 
        $buffer=""; 
        $key2=""; 

        while($msg) 
        { 
                $key2=pack("H*",md5($key.$key2.$buffer)); 
                $dec_limit=strlen(substr($msg,0,16)); 
                $buffer=bytexor(substr($msg,0,16),$key2,$dec_limit); 
                $string.=$buffer; 
                $msg=substr($msg,16); 
        } 


        return($string); 
} 

function encrypt_md5($msg,$key) 
{ 


	if ($msg=='' ) return '' ; 
	
	$key  = md5($key);

         $msg_len = strlen($msg); 
        $string=""; 
        $buffer=""; 
        $key2=""; 

        while($msg) 
        { 
                $key2=pack("H*",md5($key.$key2.$buffer)); 
                $buffer=substr($msg,0,16); 
                $enc_limit=strlen($buffer); 
                $string.=bytexor($buffer,$key2,$enc_limit); 
                $msg=substr($msg,16); 
        } 
		
	$string =  base64_encode($string) ; 
        return($string); 
} 
?>