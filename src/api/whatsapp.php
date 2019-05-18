<?php
    use Twilio\Rest\Client;

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 2:20 AM
     */
    class whatsappClient extends request
    {
        public function replyMessage($data = [])
        {
            $config = include_once dirname(dirname(dirname(__FILE__))) . "/config.php";
            Logger::debug($config);
            $config  = $config['whatsapp'];
            $twilio  = new Client($config['TWILIO_ACCOUNT_SID'], $config['TWILIO_AUTH_TOKEN']);
            $message = $twilio->messages->create($data['to'], // to
                $data['options']);

            Logger::debug(['path' => '/whatsappClient', 'data' => $data]);

            return $message;
        }
    }