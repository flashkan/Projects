<?php

namespace App\server\services;

class DB
{
    private $configElement = 'PDO';
    private $config;

    public function __construct()
    {
        $this->config = Config::get($this->configElement);
    }

    private function getConnect()
    {
        $mysqlHost = $this->config['mysqlHost'];
        $dbName = $this->config['dbName'];
        $user = $this->config['user'];
        $password = $this->config['password'];
        return new \PDO("mysql:host=$mysqlHost;dbname=$dbName", $user, $password);
    }

    public static function querySelect($query)
    {
        $pdo = (new DB())->getConnect();
        $response = $pdo->query($query, \PDO::FETCH_ASSOC);
        $arr = [];
        while ($row = $response->fetch()) {
            $arr[] = $row;
        }

        return $arr;
    }

    public
    static function select($table, $fields = '*')
    {
        $pdo = (new DB())->getConnect();
        $response = $pdo->query("SELECT $fields FROM $table;", \PDO::FETCH_ASSOC);
        $arr = [];
        while ($row = $response->fetch()) {
            $arr[] = $row;
        }
        return $arr;
    }

    public
    static function insert($table, array $post)
    {
        if (($countArr = count($post) - 1) < 0) {
            var_dump('error: $post < 0 in ' . __METHOD__);
            return;
        }

        $autofillValue = '?, ';
        $repeatAutofillValue = str_repeat($autofillValue, $countArr);
        $fields = implode(array_keys($post), ', ');
        $sql = "INSERT INTO $table ($fields) VALUES($repeatAutofillValue?);";
        $pdo = (new DB())->getConnect();
        $statement = $pdo->prepare($sql);
        return $statement->execute(array_values($post));
    }

    public
    static function delete($table, $id)
    {
        if (is_numeric($id)) {
            $pdo = (new DB())->getConnect();
            $sql = "DELETE FROM $table WHERE id = ?;";
            $statement = $pdo->prepare($sql);
            return $statement->execute([$id]);
        }
    }
}