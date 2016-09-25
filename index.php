<?php
    // validate request for external service
    require_once(dirname(__FILE__) . '/vendor/autoload.php');
    include_once dirname(__FILE__) . '/src/utils/Logger.php';
    include_once dirname(__FILE__) . 'src/api/request.php';
    include_once 'app.php';
    $app = NULL;
    if (!empty($_REQUEST['service_name'])) {
        $app = app::getInstance($_REQUEST['service_name'], $_REQUEST);
        // to app server
        if (!empty($_GET['company_id'])) {
            $app->queue($_REQUEST);
        } // to third party service
        elseif (!empty($_GET['app_id']) && !empty($_REQUEST['data'])) {
            $app->reply($_REQUEST['data']);
        }
    }
    exit;