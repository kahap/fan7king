<?php
session_start();
include('../model/php_model.php');
$member = new Member();
/*$school = new School();

if($_POST['memclass'] == '0'){
//學生資訊更新
if($_POST['school'] != ""){
$schoolName = $school->getOne($_POST['school']);
array_filter($_POST['department']);
foreach($_POST['department'] as $key => $value){
if($value != ""){
$department_data = $value;
}
}
if($department_data == ""){
$errg[] = "請選擇系所";
}
}else{
$errg[] = "請選擇學校";
}

if($_POST['memName'] == ""){
$errg[] = "請填寫姓名";
}

if($_POST['classyear'] == ""){
$errg[] = "請填寫年級";
}
$msg = implode(',',$errg);
if($errg == ""){
$memSchool = array($schoolName['0']['schName'],$department_data,$_POST['classyear']);
$_POST['memSchool'] = json_encode($memSchool, JSON_UNESCAPED_UNICODE);
echo $member->update_information_stu($_POST,$_SESSION['user']['memNo']);
}else{
echo $msg;
}
}else{
//非學生
$columeName = array('memCompanyName'=>'公司名稱','memYearWorked'=>'年資','memSalary'=>'月薪','memCompanyPhone'=>'公司電話');
foreach($_POST as $key => $value){
if(array_key_exists($key,$columeName)){
if($value != ""){
$$key = $value;
}elseif($key != 'memExnumber'){
$errg[] = "請填寫".$columeName[$key];
}

}
}
$msg = implode(',',$errg);
if($errg == ""){
$_POST['memCompanyPhone'] = ($_POST['memExnumber'] != "") ? $_POST['memCompanyPhone'].'#'.$_POST['memExnumber']:$_POST['memCompanyPhone'];
echo $member->update_information_emy($_POST,$_SESSION['user']['memNo']);
}else{
echo $msg;
}

}*/
$item = ($_GET['item'] == 0) ? '0':'4';
$member_data = $member->getOneMemberByNo($_SESSION['user']['memNo']);

if($item == '0'){
    $member->update_information_stu($item ,$_SESSION['user']['memNo']);
}else{
    $member->update_information_emy($item ,$_SESSION['user']['memNo']);
}

$_SESSION['user']['memClass'] =  $item;
echo 1;


?>