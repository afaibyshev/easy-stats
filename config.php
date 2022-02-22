<?php

if (ISINCLUDED != '1') {
  die('ACCESS DENIED!');
}

/**
 * Folder to store cache file. They can be public available.
 */
const CACHE_FOLDER = './cache/';
/**
 * Default tyle for stats and other keys.
 */
const DEFAULT_STAT_TYPE = 'global';
const STAT_ONLINE = 'online';
const STAT_EPIC = 'epic';
/**
 * Multiplier for online
 */
const STAT_ONLINE_X = 13;



/**
 * $config is an array of available servers, configuration for each server and settings for connection for it.
 */
//$config = [
//  '1' => [ // Server ID, can be string. We use the same ID on FRONT side. Also using for cache files.
//    'name' => 'Server 1', // Just label, if needed.
//    'host' => '127.0.0.1', // DB host
//    'db' => 'l2', // DB name
//    'user' => 'admin', // DB user
//    'pass' => 'admin', // Db password
//    'port' => '3306', // DB port
//    'delay' => '3600', // Cache lifetime in seconds
//    'epic' => [ // Array of available items for epic statistic.
//      6662 => ['name' => 'Ring of Core', 'grade' => 'a'], // Key is ID of item.
//      6661 => ['name' => 'Earring of Orfen', 'grade' => 'a'],
//      6660 => ['name' => 'Ring of Queen Ant', 'grade' => 'b'],
//      6659 => ['name' => 'Earring of Zaken', 'grade' => 's'],
//      6658 => ['name' => 'Ring of Baium', 'grade' => 's'],
//      6657 => ['name' => 'Necklace of Valakas', 'grade' => 's'],
//      6656 => ['name' => 'Earring of Antharas', 'grade' => 's'],
//      8191 => ['name' => 'Necklace of Frintezza', 'grade' => 'a'],
//    ],
//  ],
//];
$config = [
  '2' => [
    'name' => 'Server 2',
    'host' => '127.0.0.1',
    'db' => 'game',
    'user' => 'root',
    'pass' => 'root',
    'port' => '2345',
    'delay' => '1',
    'epic' => [
      6662 => ['name' => 'Ring of Core', 'grade' => 'a'],
      6661 => ['name' => 'Earring of Orfen', 'grade' => 'a'],
      6660 => ['name' => 'Ring of Queen Ant', 'grade' => 'b'],
      6659 => ['name' => 'Earring of Zaken', 'grade' => 's'],
      6658 => ['name' => 'Ring of Baium', 'grade' => 's'],
      6657 => ['name' => 'Necklace of Valakas', 'grade' => 's'],
      6656 => ['name' => 'Earring of Antharas', 'grade' => 's'],
      8191 => ['name' => 'Necklace of Frintezza', 'grade' => 'a'],
    ],
  ],
];

/**
 * Some setting for PDO.
 */
$options = [
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];