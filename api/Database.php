<?php

class Database {
    public function __construct(
        private string $host,
        private string $name,
        private string $username,
        private string $password) 
        {}

    public function get_connection(): PDO {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
        return new PDO(
            $dsn,
            $this->username,
            $this->password,
            [PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_STRINGIFY_FETCHES => false]
        );
    }
}