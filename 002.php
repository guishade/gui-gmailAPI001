<?php

require __DIR__ . '/func.php';

$to = 'remitent@gmail.com';
$subject = 'subject';
$messageText = 'mailbody';

sendGmail($to, $subject, $messageText);

header('Location: 001.html');

?>