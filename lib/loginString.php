<?php

function isValidUsername($sName) {
   return preg_match("/^[a-z0-9_\.-]{3,15}$/", $sName);
}

function stripAccents($str){
  $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
   $str = strtr($str, $unwanted_array);
   $str = preg_replace("/[^A-Za-z]/", '', $str);
   return $str;
}

function genLogin($db, $firstName, $lastName, $prefix) {
   srand(time() + rand());
   $charsAllowed = "0123456789";
   $query = "SELECT ID as nb FROM users WHERE sLogin = :sLogin;";
   $stmt = $db->prepare($query);
   $firstName = stripAccents($firstName);
   $lastName = stripAccents($lastName);
   $base = $prefix.strtolower(mb_substr($lastName, 0, 10, 'UTF-8')).strtolower(mb_substr($firstName, 0, 1, 'UTF-8'));
   while(true) {
      $login = $base;
      for ($pos = 0; $pos < 3; $pos++) {
         $iChar = rand(0, strlen($charsAllowed) - 1);
         $login .= substr($charsAllowed, $iChar, 1);
      }
      $stmt->execute(array('sLogin' => $login));
      $row = $stmt->fetchObject();
      if (!$row) {
         return $login;
      }
      error_log("Error, login ".$login." is already used");
   }
}