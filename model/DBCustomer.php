<?php
require_once('db.php');

/**
 * Class DBCustomer
 */
class DBCustomer extends DB
{
    //CustomerテーブルのCRUD担当

    /**
     *顧客情報を更新するためのsqlを作成する
     *
     * @return void
     */
    public function UpdateCustomer()
    {
        $sql = "UPDATE customer SET CustomerName=?, TEL=?, Email=?, WHERE CustomerID=?";
        $array = array($_POST['CustomerName'], $_POST['TEL'], $_POST['Email'], $_POST['CustomerID']);
        parent::executeSQL($sql, $array);
    }

    /**
     * IDから更新対象の名前を取得する
     *
     * @param string $CustomerID
     * @return bool|PDOStatement
     */
    public function CustomerNameForUpdate($CustomerID)
    {
        return $this->FieldValueForUpdate($CustomerID, "CustomerName");
    }

    /**
     * IDから更新対象の電話番号を取得
     *
     * @param   int $CustomerID
     * @return  bool|PDOStatement
     */
    public function TELForUpdate($CustomerID)
    {
        return $this->FieldValueForUpdate($CustomerID, "TEL");
    }

    /**
     * IDから更新対象のメールアドレスを取得する
     *
     * @param string $CustomerID
     * @return bool|PDOStatement
     */
    public function EmailForUpdate($CustomerID)
    {
        return $this->FieldValueForUpdate($CustomerID, "Email");
    }

    /**
     * 指定したIDの顧客情報を取得する
     * 取得できなかった場合falseを返す
     *
     *
     * @param int $CustomerID
     * @param string $field
     * @return bool|PDOStatement
     */
    private function FieldValueForUpdate($CustomerID, $field)
    {
        //引数の値を取得
        $sql = "SELECT {$field} FROM customer WHERE CustomerID=?";
        $array = array($CustomerID);
        $res = parent::executeSQL($sql, $array);
        $rows = $res->fetch(PDO::FETCH_NUM);
        return $rows[0];
    }

    /**
     * 顧客情報を削除する
     *
     * @param   int $CustomerID
     * @return void
     */
    public function DeleteCustomer($CustomerID)
    {
        $sql = "DELETE FROM customer WHERE CustomerID=?";
        $array = array($CustomerID);
        parent::executeSQL($sql, $array);
    }

    /**
     * 顧客情報を新規登録する
     *
     * @return void
     */
    public function InsertCustomer()
    {
        $sql = "INSERT INTO customer VALUE (?,?,?,?)";
        $array = array($_POST['CustomerID'], $_POST['CustomerName'], $_POST['TEL'], $_POST['Email']);
        parent::executeSQL($sql, $array);
    }

    /**
     * 選択した顧客の情報を取得する
     *
     * @return string $data
     */
    public function SelectCustomerAll()
    {
        $sql = "SELECT * FROM customer";
        $res = parent::executeSQL($sql, null);
        $data = "<table class='recordlist'>";
        $data .= "<tr><th>ID</th><th>顧客名</th><th>TEL</th><th>Email</th><th></th><th></th></tr>\n";
        foreach ($rows = $res->fetchAll(PDO::FETCH_NUM) as $row) {
            $data .= "<tr>";
            for ($i = 0; $i < count($row); $i++) {
                $data .= "<td>{$row[$i]}</td>";
            }
            //更新ボタンのコード
            $data .= <<<eof
            <td><form method="post" action="">
            <input type="hidden" name="id" value="{$row[0]}">
            <input type="submit" name="update" value="更新">
            </form></td>
eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td><form method="post" action="">
            <input type="hidden" name="id" value="{$row[0]}">
            <input type="submit" name="delete" id="delete" value="削除" onclick="return CheckDelete()">
            </form></td> 
eof;
            $data .= "<tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }
}
