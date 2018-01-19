<?php
require_once('db.php');
// extends DB = DBというクラスを継承している
// DB...親クラス DBGoods...子クラス
class DBGoods extends DB
{
    // goodsテーブルのCRUD担当

    /**
     * 全ての商品を取得する
     *
     * @return string
     */
    public function SelectGoodsAll()
    {
        $sql = "select * from goods";
        $res = parent::executeSQL($sql, null);
        $data = "<table class='recordlist' id='goodsTable'>";
        $data .= "<tr><th>ID</th><th>商品名</th><th>単価</th><th></th><th></th></tr>\n";
        // 繰り返し処理
        foreach ($rows = $res->fetchAll(PDO::FETCH_NUM) as $row)
        {
            $data .= "<tr>";
            for ($i=0; $i<count($row); $i++) {
                $data .= "<td>{$row[$i]}</td>";
            }
            //更新ボタンのコード
            $data .= <<<eof
            <td><form method="post" action="">
            <input type="hidden" name="id" value="{$row[0]}">
            <input type="submit" name="update" value=" 更新 ">
            </form></td>  
eof;
            //削除ボタンのコード
            $data .= <<<eof
            <td><form method="post" action="">
            <input type="hidden" name="id" id="Deleteid" value="{$row[0]}">
            <input type="submit" name="delete" id="delete" value=" 削除 " ONCLICK="return CheckDelete()">
            </form></td>  
eof;
            $data .= "<tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    /**
     * 商品の新規登録
     *
     * @return void
     */
    public function InsertGoods()
    {
        $sql = "insert into goods VALUES(?,?,?)";
        $array = array($_POST['GoodsID'], $_POST['GoodsName'], $_POST['Price']);
        // 親クラスの関数を使用する時は parent::関数名(); と表記する
        parent::executeSQL($sql, $array);
    }

    /**
     * 商品情報を更新するためのsqlを作成する
     *
     * @return void
     */
    public function UpdateGoods()
    {
        $sql = "update goods set GoodsName=?, Price=? WHERE GoodsID=?";
        $array = array($_POST['GoodsName'], $_POST['Price'], $_POST['GoodsID']);
        parent::executeSQL($sql, $array);
    }

    /**
     * 選択したIDの商品名を変更する
     *
     * @param string $GoodsID
     * @return mixed
     */
    public function GoodsNameForUpdate($GoodsID)
    {
        return $this->FieldValueForUpdate($GoodsID, "GoodsName");
    }

    /**
     * 選択したIDの商品の値段を変更する
     *
     * @param string $GoodsID
     * @return mixed
     */
    public function PriceForUpdate($GoodsID)
    {
        return $this->FieldValueForUpdate($GoodsID, "Price");
    }

    /**
     * 選択された商品の情報を更新する
     * DBGoods内だけで使用するのでprivate
     *
     * @param string $GoodsID
     * @param string $field
     * @return mixed
     */
    private function FieldValueForUpdate($GoodsID, $field)
    {
        //private関数　上の２つの関数で使用している
        $sql = "select {$field} from goods where GoodsID=?";
        $array = array($GoodsID);
        $res = parent::executeSQL($sql, $array);
        $rows = $res->fetch(PDO::FETCH_NUM);
        return $rows[0];
    }

    /**
     * 選択したIDの商品を削除する
     *
     * @param string $GoodsID
     * @return void
     */
    public function DeleteGoods($GoodsID)
    {
        $sql = "delete from goods where GoodsID=?";
        $array = array($GoodsID);
        parent::executeSQL($sql, $array);
    }
}
