<?php
/*
email
name
subject
message
captcha
*/
include 'cfconfig.php';
session_start();
$isErr = false;
$err = [];
$potentialSpam = false;
$spamMsg = '';
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$answer = $_SESSION['captcha-answer-real-contact-form'];
$userAns = intval($_POST['captcha']);
if (intval($answer) !== intval($userAns)) {
    die('Bad Captcha');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die('Bad Email');
}


$senderinfo = ['name' => $_POST['name'], 'email' => $_POST['email'], 'subject' => $_POST['subject'], 'message' => $_POST['message']];
if ((2 <= str_word_count($_POST['name'])) && (str_word_count($_POST['name']) <= 15)) {
    $potentialSpam = true;
    $spamMsg .= 'Full name is not between 2 and 15 words!\n';
}
$ports = array(8080,80,81,1080,6588,8000,3128,553,554,4480);
foreach($ports as $port) {
    if (@fsockopen($_SERVER['REMOTE_ADDR'], $port, $errno, $errstr, 30)) {
        $potentialSpam = true;
        $spamMsg .= 'It looks like the user may be using an anonymous proxy or VPN service. Server is not 100% sure, however. \n';
    }
}
$proxy_headers = array(
    'HTTP_VIA',
    'HTTP_X_FORWARDED_FOR',
    'HTTP_FORWARDED_FOR',
    'HTTP_X_FORWARDED',
    'HTTP_FORWARDED',
    'HTTP_CLIENT_IP',
    'HTTP_FORWARDED_FOR_IP',
    'VIA',
    'X_FORWARDED_FOR',
    'FORWARDED_FOR',
    'X_FORWARDED',
    'FORWARDED',
    'CLIENT_IP',
    'FORWARDED_FOR_IP',
    'HTTP_PROXY_CONNECTION'
);
foreach($proxy_headers as $x){
    if (isset($_SERVER[$x])) {
        $potentialSpam = true;
        $spamMsg .= 'It looks like the user may be using a proxy (Not anonymous) or VPN service. Server is not 100% sure, however. \n';
    }
}

if ($_POST['phone_number']) {
    $potentialSpam = true;
    $spamMsg .= 'It looks like this user may be a bot. This user may also have used autofill. This user filled out an invisible form called "phone_number". \n';
}
require_once 'spamfilter/spamfilter.php';


$filter = new SpamFilter();

$result = $filter->check_text($_POST['message']);
if ($result) {
    $potentialSpam = true;
    $spamMsg .= 'This message has potential spam. The following word was detected: ' . $result . ' \n';
}

$spamMsg = '';
if ($potentialSpam) {
    $spamTxt = 'This message looks like potential spam. Please review the following:
' . $spamMsg . '
This user\'s IP address is: ' . $ip . '
';
} else {
    $spamTxt = 'This user\'s IP address is: ' . $ip . '
';
}
$txt = 'New contact form message from ' . $contact_form_name . '!
Visitor IP Address: ' . $ip . '
Subject: "'. $senderinfo['subject'] .'"
Message below:
* ------------- *
' . $senderinfo['message'] . '
* ------------- *
[End of Message]
Sender\'s Full Name:
' . $senderinfo['name'] . '


POTENTIAL SPAM INFORMATION:
* ------------- *
Messages:
' . $spamTxt . '
Captcha:
 - Math problem: "' . $_SESSION['captcha-answer-real-contact-form'] . '"
 - Returned Answer: "' . $_POST['captcha'] . '"
';
$headers = "From: " . addslashes($senderinfo['email']);

mail($your_email,'New Contact Form Message!',$txt,$headers);
echo 'Your message has been sent! Thank you!';
session_destroy();