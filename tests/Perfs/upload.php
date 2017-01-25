<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

include __DIR__.'/../bootstrap.php';
$credentials = include(__DIR__."/imgur.credentials.php");

$version = (string)$_SERVER['argv'][1];

$fileName = __DIR__."/../../{$version}_graph.png";
if (!file_exists($fileName)) {
    echo "PNG($fileName) does not exist.";
    exit(1);
}

$file    = file_get_contents($fileName);
$headers = ["Authorization: Client-ID {$credentials['client_id']}"];
$url     = 'https://api.imgur.com/3/image.json';

$where = getenv("TRAVIS_JOB_NUMBER") ? "Travis #".getenv("TRAVIS_JOB_NUMBER") : gethostname()." - ".date("Y-m-d H:i:s").
                                                                                " - ".date_default_timezone_get();
$pvars = [
    'album'       => $credentials['ablum_id'],
    'name'        => "Build {$version}",
    'description' => "Build {$version} on ".$where,
    'image'       => base64_encode($file)
];
$curl  = curl_init();
curl_setopt_array(
    $curl,
    [
        CURLOPT_URL            => $url,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_POST           => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POSTFIELDS     => $pvars
    ]
);
$json_returned = curl_exec($curl);
$data          = json_decode($json_returned);
curl_close($curl);
echo "Graph: Build {$version} on {$where} available {$data->data->link}\n";
?>
