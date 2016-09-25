<?php

    use GuzzleHttp\Client;

    include_once dirname(dirname(__FILE__)) . '/utils/Logger.php';

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 2:20 AM
     */
    class request extends Client {

        public function __construct($config = array()) {
            $defaults = [
                // Base URI is used with relative requests
                'base_uri' => 'https://shashwatkumar.com',
                // You can set any number of default request options.
                'timeout'  => 2.0,
            ];
            parent::__construct(array_merge($defaults, $config));
        }

        public function createMessage($data = array()) {
            Logger::debug(['config' => $this->getConfig(), 'request_data' => $data]);
            $response = $this->request('POST', '/newMessage', ['form_params' => $data]);
            Logger::debug(['config' => $this->getConfig(), 'response_data' => $response]);

            return $response;
        }
    }