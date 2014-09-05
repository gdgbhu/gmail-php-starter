<?php
session_start();

// Include Autoloader
require 'vendor/autoload.php';
require 'helpers/helpers.php';

// Get API Credentials
$config = parse_ini_file('helpers/config.ini');
$notice = '';
$authException = false;
$mime = new Mail_mime();
// Setup Google API Client
$client = new Google_Client();
$client->setClientId($config['client_id']);
$client->setClientSecret($config['client_secret']);
$client->setRedirectUri($config['redirect_url']);
$client->addScope('https://mail.google.com/');

// Create GMail Service
$service = new Google_Service_Gmail($client);

// Check if user is logged out
if(isset($_REQUEST['logout'])){
    unset($_SESSION['access_token']);
}

// Check if we have an authorization code
if(isset($_GET['code'])){
    $code = $_GET['code'];
    $client->authenticate($code);
    $_SESSION['access_token'] = $client->getAccessToken();
    $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header('Location: ' . filter_var($url,FILTER_VALIDATE_URL));
}

// Check if we have an access token in the session
if(isset($_SESSION['access_token'])){
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $loginUrl = $client->createAuthUrl();
}

// Check if we have an access token ready for API calls
try
{
    if(isset($_SESSION['access_token']) && $client->getAccessToken()){
        // Make API Calls
        if(isset($_POST['send'])){
            $to = $_POST['to'];
            $bcc = $_POST['bcc'];
            $cc = $_POST['cc'];
            $body = $_POST['message'];
            $subject = $_POST['subject'];

            $mime->addTo($to);
            $mime->addBcc($bcc);
            $mime->addCc($cc);
            $mime->setTXTBody($body);
            $mime->setHTMLBody($body);
            $mime->setSubject($subject);
            $message_body = $mime->getMessage();

            $encoded_message = base64url_encode($message_body);

            // Gmail Message Body
            $message = new Google_Service_Gmail_Message();
            $message->setRaw($encoded_message);

            // Send the Email
            $email = $service->users_messages->send('me',$message);
            if($email->getId()){
                $notice = '<div class="alert alert-success">Email Sent successfully!</div>';
            } else {
                $notice = '<div class="alert alert-danger">Oops...something went wrong, try again later</div>';
            }
        } else if(isset($_POST['draft'])){
            $to = $_POST['to'];
            $bcc = $_POST['bcc'];
            $cc = $_POST['cc'];
            $body = $_POST['message'];
            $subject = $_POST['subject'];

            $mime->addTo($to);
            $mime->addBcc($bcc);
            $mime->addCc($cc);
            $mime->setTXTBody($body);
            $mime->setHTMLBody($body);
            $mime->setSubject($subject);
            $message_body = $mime->getMessage();

            $encoded_message = base64url_encode($message_body);

            // Gmail Message Body
            $message = new Google_Service_Gmail_Message();
            $message->setRaw($encoded_message);

            // Gmail Draft
            $draft_body = new Google_Service_Gmail_Draft();
            $draft_body->setMessage($message);

            // Save as Draft
            $draft = $service->users_drafts->create('me',$draft_body);
            if($draft->getId()){
                $notice = '<div class="alert alert-success">Draft saved successfully!</div>';
            } else {
                $notice = '<div class="alert alert-danger">Oops...something went wrong, try again later</div>';
            }
        }

        // Inbox Messages
        $list = $service->users_messages->listUsersMessages('me',['maxResults' => 5, 'q' => 'is:inbox']);
        $messageList = $list->getMessages();

        $client->setUseBatch(true);
        $batch = new Google_Http_Batch($client);

        foreach($messageList as $mlist){
            $batch->add($service->users_messages->get('me',$mlist->id,['format' => 'raw']),$mlist->id);
        }

        $batchMessages = $batch->execute();

        $inboxMessage = [];

        foreach($batchMessages as $dMessage){
            $messageId = $dMessage->id;
//            $gMessage = $service->users_messages->get('me',$messageId,['format' => 'raw']);
            $dcMessage = base64url_decode($dMessage->getRaw());

            $mimeDecode = new Mail_mimeDecode($dcMessage);
            $mimeSubject = $mimeDecode->decode()->headers['subject'];

            $inboxMessage[] = [
                'messageId' => $messageId,
                'messageSubject' => $mimeSubject
            ];
        }
    }

}
catch (Google_Auth_Exception $e)
{
	$authException = true;
}
