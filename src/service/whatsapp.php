<?php

    include_once dirname(dirname(__FILE__)) . '/utils/Logger.php';
    include_once dirname(dirname(__FILE__)) . '/api/whatsapp.php';

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 1:51 AM
     */
    class whatsapp
    {
        /**
         * Request to API
         *
         * @param string $url
         * @param array  $data
         * @param string $type Type of request (GET|POST|DELETE)
         *
         * @return array
         */

        public $payload = [];

        public function __construct($token = null)
        {
        }

        public function call($url, $data, $type = self::TYPE_POST)
        {
            Logger::debug(['url' => $url, 'data' => $data, 'type' => $type]);
        }

        public function reply($data)
        {
            if ($this->validate($data)) {
                $api    = new whatsappClient();
                $params = [
                    'to'      => 'whatsapp:+' . $data['sender_id'],
                    'options' => [
                        "from" => 'whatsapp:+14155238886', // Bigrock Business account
                        "body" => $data['message'],
                    ],
                ];

                /*$params = [
                    'to'      => "whatsapp:+918007766821",
                    'options' => [
                        "from" => "whatsapp:+14155238886",
                        "body" => "Hello there! How are you doing?",
                    ],
                ];*/
                $api->replyMessage($params);
            }
        }

        public function validate($data)
        {
            if (!empty($data['sender_id']) && !empty($data['message'])) {
                return true;
            }

            return false;
        }

        public function queue($data)
        {
            if ($this->hasPayload() && !empty($data['company_id'])) {
                $api = new whatsappClient();

                $params = [
                    'company_id'   => $data['company_id'],
                    'sender_id'    => $this->getSenderIdFromPayload(),
                    'receiver_id'  => $this->getReceiverIdFromPayload(),
                    'service_name' => 'whatsapp',
                    'message'      => $this->getMessageFromPayload(),
                ];
                $api->createMessage($params);
            }
        }

        public function hasPayload()
        {
            return !empty($this->payload['SmsMessageSid']) ? true : false;
        }

        // Send to whatsapp

        public function getSenderIdFromPayload()
        {
            return $this->payload['From'];
        }

        // Send to support API

        public function getReceiverIdFromPayload()
        {
            return $this->payload['To'];
        }

        public function getMessageFromPayload()
        {
            return $this->payload['Body'];
        }

        public function handle_challenge()
        {
            return false;
        }

    }