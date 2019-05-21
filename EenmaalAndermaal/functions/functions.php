<?php
function cleanInput($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
function file_url_contents($url){
  $crl = curl_init();
  $timeout = 30;
  curl_setopt ($crl, CURLOPT_URL,$url);
  curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
  $ret = curl_exec($crl);
  curl_close($crl);
  return $ret;
} //file_url_contents ENDS

//To remove all the hidden text not displayed on a webpage
function strip_html_tags($str){
  $str = preg_replace('/(<|>)\1{2}/is', '', $str);
  $str = preg_replace(
      array(// Remove invisible content
          '@<head[^>]*?>.*?</head>@siu',
          '@<style[^>]*?>.*?</style>@siu',
          '@<script[^>]*?.*?</script>@siu',
          '@<noscript[^>]*?.*?</noscript>@siu',
          ),
      "", //replace above with nothing
      $str );
  $str = replaceWhitespace($str);
  $str = strip_tags($str);
  return $str;
} //function strip_html_tags ENDS

//To replace all types of whitespace with a single space
function replaceWhitespace($str) {
  $result = $str;
  foreach (array(
  "  ", " \t",  " \r",  " \n",
  "\t\t", "\t ", "\t\r", "\t\n",
  "\r\r", "\r ", "\r\t", "\r\n",
  "\n\n", "\n ", "\n\t", "\n\r",
  ) as $replacement) {
  $result = str_replace($replacement, $replacement[0], $result);
  }
  return $str !== $result ? replaceWhitespace($result) : $result;
}

function stripHTMLEntity($text){
  $Content = preg_replace("/&#?[a-z0-9]{2,8};/i","",$text);
  return $Content;
}
?>
