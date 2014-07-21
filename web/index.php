<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

$allowed_device_ids = array(
    'RM-AAU104'
    );
$allowed_command_ids = array(
    'KEY_POWER', // Amp power
    'KEY_POWER2', // TV power
    'KEY_SELECT', // TV input select
    'KEY_1', // BD/DVD
    'KEY_2', // GAME
    'KEY_3', // SAT/CATV
    'KEY_4', // VIDEO (APPLE TV)
    'KEY_5', // TV
    'KEY_6', // SA-CD
    'KEY_7', // TUNER
    'KEY_8', // PORTABLE
    'KEY_VOLUMEUP', // Amp volume up
    'KEY_VOLUMEDOWN', // Amp volume down
    'KEY_SOUND', // SOUND FIELD
    'KEY_MUTE', // Amp mute
    'KEY_MODE' // INPUT MODE
    );
$allowed_send_types = array(
    'SEND_ONCE',
    'SEND_START',
    'SEND_STOP'
    );

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/{device_id}/{command_id}/{send_type}',
function (Silex\Application $app, $device_id, $command_id, $send_type)
use ($allowed_device_ids,$allowed_command_ids,$allowed_send_types)
{
    // Validate user inputs
    if (!in_array($device_id,$allowed_device_ids) ||
        !in_array($command_id,$allowed_command_ids) ||
        !in_array($send_type,$allowed_send_types)
    ) {
        $app->abort(404, 'ERROR: Unexpected device id, command id, or send type.');
    }

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

$app->get('/', function() {
    return 'Hello world!';
});

$app->run();
