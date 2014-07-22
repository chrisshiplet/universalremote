<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/models/Device.php';

use Symfony\Component\HttpFoundation\Response;

$allowed_device_ids = array();
$allowed_device_ids[0] = new Device("RM-AAU104");
$allowed_device_ids[0]->commands = array(
    'KEY_POWER', // Amp power
    'KEY_POWER2', // TV power
    'KEY_VOLUMEDOWN', // Amp volume down
    'KEY_VOLUMEUP', // Amp volume up
    'KEY_MUTE', // Amp mute
    'KEY_SOUND', // SOUND FIELD
    'KEY_SELECT', // TV input select
    'KEY_MODE', // INPUT MODE
    'KEY_1', // BD/DVD
    'KEY_2', // GAME
    'KEY_3', // SAT/CATV
    'KEY_4', // VIDEO (APPLE TV)
    'KEY_5', // TV
    'KEY_6', // SA-CD
    'KEY_7', // TUNER
    'KEY_8' // PORTABLE
    );
$allowed_device_ids[0]->labels = array(
    'AMP POWER',
    'TV POWER',
    'VOL -',
    'VOL +',
    'AMP MUTE',
    'SOUND FIELD',
    'INPUT SELECT',
    'INPUT MODE',
    'BD/DVD',
    'GAME',
    'SAT/CATV',
    'APPLE TV',
    'TV',
    'SA-CD',
    'TUNER',
    'PORTABLE'
    );
$allowed_device_ids[0]->key_type = array(
    0,
    0,
    1,
    1,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    0
    );

$allowed_send_types = array(
    'SEND_ONCE',
    'SEND_START',
    'SEND_STOP'
    );

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/{device_id}/{command_id}/{send_type}',
function (Silex\Application $app, $device_id, $command_id, $send_type)
use ($allowed_device_ids,$allowed_send_types)
{
    // Validate user inputs
    // TODO WRITE THIS

    // Attempt to execute command
    exec('irsend '.escapeshellarg($send_type).' '.escapeshellarg($device_id).' --count=3 '.escapeshellarg($command_id).' 2>&1',$output,$retval);
    $output_r = print_r($output,TRUE);

    // Validate execution
    if ($retval != 0) {
        $app->abort(404, 'ERROR: '.$output_r);
    } else {
        return 'SUCCESS';
    }
})
->value('send_type', 'SEND_ONCE');

$app->get('/', function() use ($app) {
    return $app['twig']->render('default.twig');
});

$app->run();
