<?php



function parseJson($urls) {
  $c = curl_init($urls);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0");
  curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
  $content = curl_exec($c);
  return $content;      
};


function download_image ($url, $name, $directory ) {
  if (!is_dir($directory)) {
    mkdir($directory);       
  }

  $image_format= substr($url, strrpos($url, ".") - strlen($url));
  $rawImage = file_get_contents($url);

  if($rawImage) {
 file_put_contents($directory . "/" . $name . $image_format, $rawImage);
//  header("Location: card.php?Comment=Image Saved&image_format=$image_format");
 }
 else
 {
  // header("Location: card.php?Comment=No Image");
 }

 }


 function image_extension($url) {


  return substr($url, strrpos($url, ".") - strlen($url));


 }






