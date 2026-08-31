<?php
session_start();

/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

/*if(isset($_POST['sssess'])){
    $_SESSION['sscansem_register'] = true;
    exit;
}

if(!isset($_SESSION['sscansem_register'])){
    http_response_code(400);
}*/

function send_mail($to = array("hussainmh39@gmail.com"), $to_name = array("Hussain"), $subject = "Test", $message = "Test")
{
    if (!function_exists('set_magic_quotes_runtime')) {
        function set_magic_quotes_runtime($new_setting)
        {
            return true;
        }
    }

    include_once "libs/PHPMailer/class.phpmailer.php";
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = "smtp.hostinger.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "testing@peachinfotech.com";
    $mail->Password = 'j#Gz7;i0q&~Qs;';
    $mail->SetFrom("testing@peachinfotech.com", "First Fly Aviation");
    $mail->Subject = $subject;
    $mail->Body = $message;
    foreach ($to as $k => $t) {
        $mail->AddAddress($t, $to_name[$k]);
    }
    return $mail->Send();
}

if (!isset($_SESSION['cfs'])) {
    $response = array('status' => 'error', 'message' => 'Oops.! Nice try though..!');
    echo json_encode($response);
    exit;
}

if (!$_POST) http_response_code(400);

$protocol       = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
$root           = "$protocol://$_SERVER[HTTP_HOST]";
$root           .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
$is_local       = $_SERVER['HTTP_HOST'] == 'localhost';
$base_url       = 'http://localhost/sites/firstflyaviation.in';
if (!$is_local) {
    $base_url   = 'https://firstflyaviation.in/landing/';
}
$logo = $base_url . 'assets/images/logo.png';
if ($is_local) {
    $logo = 'https://firstflyaviation.in/assets/images/logo.png';
}

$post = $_POST;
$to_address = "firstflyaviation@gmail.com";
if ($is_local) {
    $to_address = "hussainmh39@gmail.com";
}

$template = file_get_contents("email-template.html");
foreach ($post as $key => $value) {
    $template = str_replace("{{" . $key . "}}", ": $value", $template);
}
//exit($template);

//echo '<pre>'; print_r($post); exit;

if (isset($post['name'])) {
    $subject = "New Enquiry from $post[name]";
    $status = send_mail(array($to_address), array("First Fly Aviation"), $subject, $template);
    $status = $status ? "success" : "error";
    $message = $status ? "Thanks for your submitting you details. We will contact you shortly" : "Error occurred. Please try again later";
    $response = array('status' => $status, 'message' => $message);
    echo json_encode($response);
    exit;
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request.');
    echo json_encode($response);
    exit;
}
