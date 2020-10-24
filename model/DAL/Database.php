<?php

namespace Model;

class Database {

    private $serverSettings;

    public function __construct() {
        $server = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if ($server == 'localhost') {
           $this->serverSettings = new \LocalSettings();
        } else {
           $this->serverSettings = new \ProductionSettings();
        }
    }

    public function connect() {
        $dsn = 'mysql:host=' . $this->serverSettings->DB_HOST . ';dbname=' . $this->serverSettings->DB_NAME;
        $pdo = new \PDO($dsn, $this->serverSettings->DB_USERNAME,$this->serverSettings->DB_PASSWORD);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }
}
