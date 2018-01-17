<?php
class DB
{
    private $user = "root";
    private $pw = "root";
    private $dns = "mysql:dbname=tsubokotsu; host=mysql; charset=utf8";

    private function ConnectDB()
    {
        try {
            // クラス内の変数を使う時は$this-> を使う
            $pdo = new PDO($this->dns, $this->user, $this->pw);
            return $pdo;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function executeSQL($sql, $array)
    {
        try {
            if (!$pdo = $this -> ConnectDB()) return false;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($array);
            return $stmt;

        // ConnectDBの戻り値がFalseならば
        } catch (Exception $e) {
            return false;
        }
    }
}
