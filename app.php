<?php


    /**
     * Created by PhpStorm.
     * User: prasad.p
     * Date: 25/09/16
     * Time: 12:58 AM
     */
    include_once dirname(__FILE__) . "/src/service/facebook.php";
    include_once dirname(__FILE__) . "/src/service/whatsapp.php";

    class app {

        public static function getInstance($service_type = NULL, $data = array()) {
            $instance = NULL;
            $service_type = strtolower($service_type);
            if (!empty($service_type)) {
                switch ($service_type) {
                    case 'facebook' :
                        $instance          = new facebook("EAARhx36Ro2gBAEZCBlcW0mJdPVHGbZC2lfG2LePzv1ZAcdb4ZBV4R6ZC9MEAEyWW1w1zw840ZC5L6WlAVkqu0OsX6rZBbEJCs66JjvBX6PSSSktfwhqPhyZAwAQs8OLgOwuM2eJqLsudoFjz1Ujrjjlq4IKVRJmTTpXB5CTzgoxNqwZDZD");
                        $instance->payload = json_decode(file_get_contents("php://input"), true, 512);
                        break;
                    case 'whatsapp':
                        $instance = new whatsapp();
                        $instance->payload = $data;
                        break;
                    case 'google_assistant':
                        $instance = new whatsapp();
                        $instance->payload = $data;
                        break;
                    case 'dca':
                        $instance = new DCA();
                        $instance->payload = $data;
                        break;
                    default:
                        Logger::error(['service_type' => $service_type, 'payload' => $data, 'message' => 'Exception: Service not found']);
                        throw new Exception('Service not found');
                }
            }

            return $instance;

        }
    }