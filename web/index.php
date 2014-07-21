<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/{device_id}/{command_id}/{send_type}', function (Silex\Application $app, $device_id, $command_id, $send_type) {
    exec('irsend '.$send_type.' '.$device_id.' --count=3 '.$command_id.' 2>&1',$output,$retval);
    $output_r = print_r($output,TRUE);
    if ($retval != 0) {
        $app->abort(404, 'ERROR: '.$output_r);
    }
    else
    {
        return 'SUCCESS';
    }
})
->value('send_type', 'SEND_ONCE');

$app->get('/', function() {
    return 'Hello world!';
});

$app->run();
