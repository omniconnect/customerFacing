<?php

    use pimax\FbBotApp;
    use pimax\Messages\Message;

    include_once dirname(dirname(__FILE__)) . '/utils/Logger.php';
    include_once dirname(dirname(__FILE__)) . '/api/request.php';
    include_once dirname(dirname(__FILE__)) . '/api/dca.php';

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 1:51 AM
     */
    class facebook extends FbBotApp
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
            parent::__construct($token);
        }

        public function hasPayload()
        {
            return !empty($this->payload['entry'][0]['messaging'][0]) ? true : false;
        }

        public function getMessageFromPayload()
        {
            return $this->payload['entry'][0]['messaging'][0]['message']['text'];
        }

        public function getSenderIdFromPayload()
        {
            return $this->payload['entry'][0]['messaging'][0]['sender']['id'];
        }

        public function call($url, $data, $type = self::TYPE_POST)
        {
            Logger::debug(['url' => $url, 'data' => $data, 'type' => $type]);
            parent::call($url, $data, $type = self::TYPE_POST);
        }

        public function reply($data)
        {
            if ($this->validate($data)) {
                $this->send(new Message($data['sender_id'], $data['message']));
            }
        }

        public function queue($data)
        {
            if ($this->hasPayload() && !empty($data['company_id'])) {
                $api    = new request();
                $params = [
                    'company_id'   => $data['company_id'],
                    'sender_id'    => $this->getSenderIdFromPayload(),
                    'service_name' => 'facebook',
                    'message'      => $this->getMessageFromPayload(),
                ];

                $re = '/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/m';
                $str = $this->getMessageFromPayload();

                $matches = NULL;
                preg_match($re, $str, $matches);
                if (empty($matches)) {
                    $api->createMessage($params);
                } else {
                    $dca = new DCAClient();
                    $response = $dca->performDCA(['domain_name' => $matches[0]]);
                    Logger::error([$response, $matches]);
                    $response['domain_name'] = $matches[0];
                    $response['sender_id']    = $this->getSenderIdFromPayload();
                    if ($response['status'] == 'available') {
                        return $api->sendButtons($response);
                    } else {
                        $response['message'] =  $response['domain_name'] . ' is unavailable :(';
                        $this->reply($response);
                    }
                }
            }
        }

        public function validate($data)
        {
            if (!empty($data['sender_id']) && !empty($data['message'])) {
                return true;
            }

            return false;
        }

        public function handle_challenge()
        {
            if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe'
                && $_REQUEST['hub_verify_token'] == 'vendetta'
            ) {
                // Webhook setup request
                die($_REQUEST['hub_challenge']);
            } else {
                // Chat Message
                return false;
            }
        }

    }