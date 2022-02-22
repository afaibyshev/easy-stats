<?php

const ISINCLUDED = 1;

ini_set('display_errors', '0');
error_reporting(E_ALL);


require 'DataProvider/ServerDataProvider.php';
require 'config.php';

if (empty($_GET['sid']) || !isset($config[$_GET['sid']])) {
    echo json_encode(['error' => 'Server was not found.']);
    exit();
}
$statType = $_GET['type'] ?? DEFAULT_STAT_TYPE;
$sid = $_GET['sid'];
$server = $config[$_GET['sid']];
$fileName = CACHE_FOLDER . $sid . '-stat-' . $statType . '.json';
$file = file_get_contents($fileName);
$time = time();
if ($file) {
    $data = json_decode($file, TRUE);
    if ($data['lastModified'] + $server['delay'] >= $time) {
        echo $file;
        exit();
    }
}

$content = ['lastModified' => 0];
try {
    // TODO: validate configuration.
    $host = $server['host'];
    $db = $server['db'];
    $user = $server['user'];
    $pass = $server['pass'];
    $port = $server['port'];
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
    $dbh = new PDO($dsn, $user, $pass, $options ?? []);
    $provider = new ServerDataProvider($dbh);
    switch ($statType) {
        case DEFAULT_STAT_TYPE:
            $content['stat'] = $provider->getGlobalStat($server);
            break;
        case STAT_ONLINE:
            $content['stat'] = $provider->getOnline();
            break;
        case STAT_EPIC:
            $content['stat'] = $provider->getEpicStat($server['epic'] ?? []);
            break;

        default:
            $content['error'] = 'TYPE NOT FOUND';
            break;
    }
    $content['lastModified'] = $time;
    $newFile = json_encode($content);
    file_put_contents($fileName, $newFile);
    echo $newFile;
    exit();
} catch (PDOException $e) {
    $content['error'] = $e->getMessage();
    echo json_encode($content);
    exit();
}