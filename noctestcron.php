<?
$fp = fopen('output.txt', 'w');

//"我\r\n愛\r\n妳"則是要寫入的文字
//而在Windows系統下的文字檔會把"\r\n"視同為「跳行」
fwrite($fp, "123");
fclose($fp);

/*      Output
**
**      我
**      愛
**      妳
*/
?>
