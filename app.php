<?php


    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 12:58 AM
     */
include "src/service/facebook.php";

    class app {

        public static function getInstance($service_type = NULL, $data = array()) {
            $instance = NULL;
            if (!empty($service_type)) {
                switch ($service_type) {
                    case 'facebook' :
                        $instance          = new facebook($data['data']['token']);
                        $instance->payload = json_decode(file_get_contents("php://input"), true, 512);
                        Logger::debug($instance->payload);
                        break;
                    default:
                        Logger::error(['service_type' => $service_type, 'payload' => $data, 'message' => 'Exception: Service not found']);
                        throw new Exception('Service not found');
                }
            }

            return $instance;

        }
    }