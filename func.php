<?php

require __DIR__ . '/vendor/autoload.php';


function sendGmail($to, $subject, $messageText) {

    $sender = 'account_for_use_by_app@gmail.com';
    
    function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Gmail API PHP Quickstart');
        $client->setScopes(Google_Service_Gmail::MAIL_GOOGLE_COM);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                //printf("Open the following link in your browser:\n%s\n", $authUrl);
                //print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    $client = getClient();
    $service = new Google_Service_Gmail($client);

    function createMessage($sender, $to, $subject, $messageText) {
        $message = new Google_Service_Gmail_Message();
        $rawMessageString = "From: <{$sender}>\r\n";
        $rawMessageString .= "To: <{$to}>\r\n";
        $rawMessageString .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
        $rawMessageString .= "MIME-Version: 1.0\r\n";
        $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n";
        $rawMessageString .= 'Content-Transfer-Encoding: quoted-//printable' . "\r\n\r\n";
        $rawMessageString .= "{$messageText}\r\n";
        $rawMessage = strtr(base64_encode($rawMessageString), array('+' => '-', '/' => '_'));
        $message->setRaw($rawMessage);
        return $message;
    }

    function createDraft($service, $sender, $message) {
        $draft = new Google_Service_Gmail_Draft();
        $draft->setMessage($message);
        try {
            $draft = $service->users_drafts->create($sender, $draft);
            //print 'Draft ID: ' . $draft->getId();
        } catch (Exception $e) {
            //print 'An error occurred: ' . $e->getMessage();
        }
        return $draft;
    }

    function sendMessage($service, $sender, $message) {
        try {
            $message = $service->users_messages->send($sender, $message);
            //print 'Message with ID: ' . $message->getId() . " sent. \n";
            return $message;
        } catch (Exception $e) {
            //print 'An error occurred: ' . $e->getMessage();
        }
        return null;
    }
    
    $message = createMessage($sender, $to, $subject, $messageText);
    sendMessage($service, $sender, $message);
    
}


?>