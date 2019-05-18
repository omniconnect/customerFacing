<?php

    use GuzzleHttp\Client;

    include_once dirname(dirname(__FILE__)) . '/utils/Logger.php';

    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 2:20 AM
     */
    class DCAClient extends Client
    {

        public function __construct($config = [])
        {
            $defaults = [
                // Base URI is used with relative requests
                'base_uri' => 'https://www.bigrock.in/',
                // You can set any number of default request options.
                'timeout'  => 111,
            ];
            parent::__construct($defaults);
        }

        public function performDCA($data = [])
        {
            //$response = $this->request('GET', 'https://www.bigrock.in/domain.php?action=caajax&domain_name=' . $data['domain_name']);

            $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            );
            $context = stream_context_create($opts);

            $response = json_decode(file_get_contents('https://www.bigrock.in/domain.php?action=caajax&domain_name=' . $data['domain_name'], false, $context), true);


            //$response = file_get_contents('https://www.bigrock.in/domain.php?action=caajax&domain_name=' . $data);
            Logger::debug(['path' => '/domain', 'data' => $data, 'response' => $response, 'this' => $this]);

            return $response;
        }
    }