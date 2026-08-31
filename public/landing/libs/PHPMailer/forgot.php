<?php
header('Access-Control-Allow-Origin: *');
//echo json_encode(array("status" => "success")); exit;
$_POST 		= $_POST ? $_POST : $_GET ;
if(!isset($_POST["name"])){
    exit("Oops!");
}
$name		= $_POST["name"];
$gender	    = $_POST["gender"];
$mobile	    = $_POST["mobile"];
$course	    = $_POST["course"];
$batch      = $_POST["batch"];
$message    = $_POST["message"];

$to         = "hussain@plestar.net";
$subject    = $name;
$message    = "
<html>
<body>
<h2>Trouble in logging in</h2>
<h3>Name : <b>$name</b></h3>
<h3>Gender : <b>$gender</b></h3>
<h3>Phone : <b>$mobile</b></h3>
<h3>Course : <b>$course</b></h3>
<h3>Batch : <b>$batch</b></h3>
<h3>Message : <b>$message</b></h3>
</body>
</html>
";
        
include "class.phpmailer.php";

$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "plestarhussain@gmail.com";
$mail->Password = "plestar4010";
$mail->SetFrom("no-reply@transworldeducare.com", "Trouble in logging in");
$mail->Subject = $subject;
$mail->Body = $message;
$mail->AddAddress($to);
$mail->AddAddress("dinesh@plestar.net");
$mail->AddAddress("anil@plestar.net");
 if(!$mail->Send()){
	echo json_encode(array("status" => "error"));
}
else{
	echo json_encode(array("status" => "success"));
}
?>