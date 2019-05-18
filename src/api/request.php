<?php

    use GuzzleHttp\Client;
    use pimax\Messages\MessageButton;
    use pimax\Messages\MessageElement;
    use pimax\Messages\StructuredMessage;

    include_once dirname(dirname(__FILE__)) . '/utils/Logger.php';

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 2:20 AM
     */
    class request extends Client
    {

        public function __construct($config = [])
        {
            $defaults = [
                // Base URI is used with relative requests
                'base_uri' => 'https://f0202364.ngrok.io/',
                // You can set any number of default request options.
                'timeout'  => 111,
            ];
            parent::__construct($defaults);
        }

        public function createMessage($data = [])
        {
            $response = $this->request('POST', '/newMessage', ['form_params' => $data]);
            Logger::debug(['path' => '/newMessage', 'data' => $data]);

            return $response;
        }

        public function sendButtons($data = []) {

            $domains[] = new MessageElement(
                'YaaY!!! ' . $data['domain_name'] . ' is ' . $data['status'] . ' for Rs ' . $data['price'], ' on Bigrock', "https://www.bigrock.in/ui/bigrock/themes/ClassicBlue/images/logo.gif",
                [
                    new MessageButton(MessageButton::TYPE_WEB, 'Buy Now', 'https://www.bigrock.in/cart-dotcomabhi?domain_name=' . $data['domain_name']),
                ]
            );

            $bot         = new facebook('EAARhx36Ro2gBAEZCBlcW0mJdPVHGbZC2lfG2LePzv1ZAcdb4ZBV4R6ZC9MEAEyWW1w1zw840ZC5L6WlAVkqu0OsX6rZBbEJCs66JjvBX6PSSSktfwhqPhyZAwAQs8OLgOwuM2eJqLsudoFjz1Ujrjjlq4IKVRJmTTpXB5CTzgoxNqwZDZD');
            return $bot->send(
                new StructuredMessage(
                    $data['sender_id'],
                    StructuredMessage::TYPE_GENERIC,
                    [
                        'elements' => $domains,
                    ]
                )
            );
        }
    }