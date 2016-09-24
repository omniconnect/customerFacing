<?php

$verify_token = ""; // Verify token
$token = ""; // Page token

if (file_exists(__DIR__.'/config.php') & empty($_GET['company_id'])) {
    $config = include __DIR__.'/config.php';
    $company_id = $_GET['company_id'];
    $company_info = $config[$company_id];
    $verify_token = $company_info['verify_token'];
    $token = $company_info['token'];
}

require_once(dirname(__FILE__) . '/vendor/autoload.php');

use pimax\FbBotApp;
use pimax\Messages\Message;
use pimax\Messages\ImageMessage;
use pimax\UserProfile;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;

// Make Bot Instance
$bot = new FbBotApp($token);

if (!empty($_REQUEST['local'])) {

    $message = new ImageMessage(1585388421775947, dirname(__FILE__).'/fb4d_logo-2x.png');

    $message_data = $message->getData();
    $message_data['message']['attachment']['payload']['url'] = 'fb4d_logo-2x.png';

        echo '<pre>', print_r($message->getData()), '</pre>';

    $res = $bot->send($message);

    echo '<pre>', print_r($res), '</pre>';
}

// Receive something
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {

    // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {

    // Other event

    $data = json_decode(file_get_contents("php://input"), true, 512, JSON_BIGINT_AS_STRING);
    if (!empty($data['entry'][0]['messaging'])) {
        foreach ($data['entry'][0]['messaging'] as $message) {

            // Skipping delivery messages
            if (!empty($message['delivery'])) {
                continue;
            }

            $command = "";

            // When bot receive message from user
            if (!empty($message['message'])) {
                $command = $message['message']['text'];

            // When bot receive button click from user
            } else if (!empty($message['postback'])) {
                $command = $message['postback']['payload'];
            }

            // Handle command
            switch ($command) {

                // When bot receive "text"
                case 'text':
                    $bot->send(new Message($message['sender']['id'], 'This is a simple text message.'));
                    break;

                // When bot receive "profile"
                case 'profile':

                    $user = $bot->userProfile($message['sender']['id']);
                    $bot->send(new StructuredMessage($message['sender']['id'],
                        StructuredMessage::TYPE_GENERIC,
                        [
                            'elements' => [
                                new MessageElement($user->getFirstName()." ".$user->getLastName(), " ", $user->getPicture())
                            ]
                        ]
                    ));

                    break;
                // Other message received
                default:
                    $bot->send(new Message($message['sender']['id'], 'Sorry. I don’t understand you.'));
            }
        }
    }
}
