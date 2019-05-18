<?php

    include_once dirname(dirname(__FILE__)) . '/utils/Logger.php';
    include_once dirname(dirname(__FILE__)) . '/api/dca.php';

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 1:51 AM
     */
    class DCA
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
                $api    = new DCAClient();
//                $params = [
//                    'to'      => 'whatsapp:+' . $data['sender_id'],
//                    'options' => [
//                        "from" => 'whatsapp:+' . $data['receiver_id'],
//                        "body" => $data['message'],
//                    ],
//                ];
                $params['domian'] = $data;
                return $api->performDCA($params);
            }

            return [
                'status' => 'unavailable'
            ];
        }

        public function validate($data)
        {
            if (!empty($data['sender_id']) && !empty($data['message'])) {
                return true;
            }

            return false;
        }

        public function hasPayload()
        {
            return !empty($this->payload['domain_name']) ? true : false;
        }

        public function handle_challenge()
        {
            return false;
        }

    }