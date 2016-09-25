<?php

    use pimax\FbBotApp;
    use pimax\Messages\Message;
    include_once '../utils/Logger.php';
    include_once '../api/request.php';

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 1:51 AM
     */
    class facebook extends FbBotApp {
        /**
         * Request to API
         *
         * @param string $url
         * @param array  $data
         * @param string $type Type of request (GET|POST|DELETE)
         *
         * @return array
         */

        public $payload = array();

        public function __construct($token = NULL) {
            parent::__construct($token);
        }

        public function getMessageFromPayload() {
            return $this->payload['entry'][0]['messaging']['text'];
        }

        public function getSenderIdFromPayload() {
            return $this->payload['entry'][0]['messaging']['sender']['id'];
        }

        protected function call($url, $data, $type = self::TYPE_POST) {
            Logger::debug(['url' => $url, 'data' => $data, 'type' => $type]);
            parent::call($url, $data, $type = self::TYPE_POST);
        }

        public function reply($data) {
            if ($this->validate($data)) {
                $this->send(new Message($data['sender_id'], $data['message']));
            }
        }

        public function queue($data) {
            if ($this->validate($data)) {
                $api    = new request();
                $params = [
                    'company_id'   => $data['company_id'],
                    'sender_id'    => $this->getSenderIdFromPayload(),
                    'service_name' => 'facebook',
                    'message'      => $this->getMessageFromPayload(),
                ];
                $api->createMessage($params);
            }
        }

        public function validate($data) {
            if (!empty($data['sender_id']) && !empty($data['message'])) {
                return true;
            }

            return false;
        }

        public function handle_challenge() {
            if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == 'vendetta') {
                // Webhook setup request
                echo $_REQUEST['hub_challenge'];
                exit;
            } else {
                return false;
            }
        }

    }