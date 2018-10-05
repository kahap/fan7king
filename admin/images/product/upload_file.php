<?php
ini_set('display_errors','1');
error_reporting(E_ALL);

/* Windows 目錄編碼big5 */
$ymonth = mb_convert_encoding('第二層', 'big5', 'UTF-8');
$mdates = mb_convert_encoding('第三層', 'big5', 'UTF-8');
$topfile = mb_convert_encoding('第一層', 'big5', 'UTF-8');

if(!is_dir($topfile)){	
	mkdir($topfile,0777); 
}
if(!is_dir($topfile.'/'.$ymonth)){	
	mkdir($topfile.'/'.$ymonth,0777); 
}
if(!is_dir($topfile.'/'.$ymonth.'/'.$mdates)){	
	mkdir($topfile.'/'.$ymonth.'/'.$mdates,0777);	
}	
$writedir = $topfile.'/'.$ymonth.'/'.$mdates;

$allowedExts = array("gif", "jpeg", "jpg", "png","mp4");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "video/mp4"))
&& ($_FILES["file"]["size"] < 2000000000)
&& in_array($extension, $allowedExts)) {
  if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "
";
  } else {
    echo "Upload: " . $_FILES["file"]["name"] . "
";
    echo "Type: " . $_FILES["file"]["type"] . "
";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB
";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "
";
    if (file_exists("$writedir/" . $_FILES["file"]["name"])) {
      echo $_FILES["file"]["name"] . " already exists. ";
    } else {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "$writedir/" . $_FILES["file"]["name"]);
      echo "<br/>Stored in: " . mb_convert_encoding($writedir,'UTF-8','big5')."/" . $_FILES["file"]["name"];
    }
  }
} else {
  echo "Invalid file";
}
?>