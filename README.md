# Easy stats script.


## Note

> I don't support it, I uploaded on github just to store code.
> I don't worry about code styles or best practices in this repo. I just spent a bit of my free time to create it for my friend.
> Probably, i will fix TODOs, but i don't have time right now.
> Feel free to contribute :)


## Installation

You need to confiture config.php file.
Example:
```
$config = [
  '1' => [ // Server ID, can be string. We use the same ID on FRONT side. Also using for cache files.
    'name' => 'Server 1', // Just label, if needed.
    'host' => '127.0.0.1', // DB host
    'db' => 'l2', // DB name
    'user' => 'admin', // DB user
    'pass' => 'admin', // Db password
    'port' => '3306', // DB port
    'delay' => '3600', // Cache lifetime in seconds
    'epic' => [ // Array of available items for epic statistic.
      6662 => ['name' => 'Ring of Core', 'grade' => 'a'], // Key is ID of item.
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
```

After it try to open test.html or epic.html file)

You need to use it on local web server, i just added docket for database (Mysql).

## Docker

Just use command.

```sh
docker-compose up -d
```


## License

MIT

