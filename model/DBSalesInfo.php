<?php
require_once('db.php');
class DBSalesInfo extends DB{
    //salesinfoテーブルのCRUD担当

    /**
     * 商品名リストの作成
     *
     * @return string
     */
    public function ListGoods(){
        $sql = "SELECT GoodsID,GoodsName,Price FROM goods ORDER BY GoodsID";
        $res = parent::executeSQL($sql,null);
        $list = "<select name='GoodsID'>\n";
        $list .= "<option value='-99'>--選択してください--</option>\n";
        foreach($rows=$res->fetchAll(PDO::FETCH_ASSOC) as $row){
            $list .= "<option value='{$row['GoodsID']}'>{$row['GoodsName']}</option>\n";
        }
        $list .= "</select>\n";
        return $list;
    }

    /**
     * 顧客IDと商品IDが選択されていたら登録する
     *
     */
    public function InsertSalesinfo(){
        if($_POST['CustomerID']>0 && $_POST['GoodsID']>0){
            $sql ="INSERT INTO salesinfo VALUES(?,?,?,?,?)";
            $array = array(null, $_POST['SalesDate'], $_POST['CustomerID'],
                $_POST['GoodsID'], $_POST['Quantity']);
            parent::executeSQL($sql,$array);
        }
    }

    /**
     * 指定期間、顧客IDに該当する売上情報を取得する
     * 取得できなかった場合はfalseを返す
     *
     * @param string $salesDate
     * @param string $customerID
     * @return bool|PDOStatement
     */
    private function getSalesinfo($salesDate, $customerID){
        $sql = <<<eof
    SELECT salesinfo.id,salesinfo.SalesDate,customer.CustomerName,goods.GoodsName,goods.Price,
    salesinfo.Quantity,goods.Price*salesinfo.Quantity
    FROM salesinfo INNER JOIN customer ON salesinfo.CustomerID=customer.CustomerID
    INNER JOIN goods ON salesinfo.GoodsID=goods.GoodsID
    WHERE salesinfo.SalesDate=? and salesinfo.CustomerID=?
    ORDER BY salesinfo.id
eof;
        $array = array($salesDate, $customerID);
        $res = parent::executeSQL($sql,$array);
        return $res;
    }

    /**
     * 日付と顧客IDで売上情報を抽出する
     *
     * @param string $salesDate
     * @param string $customerID
     * @return string
     */
    public function SelectSalesinfo($salesDate, $customerID){
        $res = $this->getSalesinfo($salesDate, $customerID);
        $data = "<table id='entryslip'>\n";
        $data .= "<tr><th>ID</th><th>日付</th><th>顧客名</th><th>商品名</th>
              <th>単価</th><th>数量</th><th>金額</th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM)as $row){
            $data .= "<tr>";
            for($i=0;$i<count($row);$i++){
                $data .= "<td>{$row[$i]}</td>";
            }
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    /**
     * 引数に取った顧客IDの顧客名リストを作成する
     *
     * @param string $CustomerID
     * @return string
     */
    public function ListCustomerWithSelected($CustomerID){
        $sql = "SELECT CustomerID,CustomerName FROM customer ORDER BY CustomerID";
        $res = parent::executeSQL($sql,null);
        $list = "<select name='CustomerID'>\n";
        $list .= "<option value='-99'>--  選択してください  --</option>\n";
        foreach($rows=$res->fetchAll(PDO::FETCH_NUM) as $row){
            $selected = ($row[0] == $CustomerID)?'selected':'';
            $list .= "<option value='{$row[0]}' {$selected}>{$row[1]}</option>\n";
        }
        $list .= "</select>\n";
        return $list;
    }

    /**
     * 顧客名リストを作成する
     *
     * @return string
     */
    public function ListCustomer(){
        $sql = "SELECT CustomerID,CustomerName FROM customer ORDER BY CustomerID";
        $res = parent::executeSQL($sql,null);
        $list = "<select name='CustomerID'>\n";
        $list .= "<option value='-99'>--  選択してください  --</option>\n";
        foreach($rows=$res->fetchAll(PDO::FETCH_NUM) as $row){
            $list .= "<option value='{$row[0]}'>{$row[1]}</option>\n";
        }
        $list .= "</select>\n";
        return $list;
    }

    /**
     * 選択したIDの売上情報を削除する
     *
     */
    public function DeleteDetail(){
        $sql = "DELETE FROM salesinfo WHERE ID=?";
        $array = array($_POST['id']);
        parent::executeSQL($sql,$array);
    }

    /**
     * 売上情報を更新するためのsqlを作成する
     *
     */
    public function UpdateDetail(){
        $sql = "UPDATE salesinfo SET SalesDate=?, CustomerID=?, GoodsID=?, Quantity=? WHERE id=?";
        $array = array($_POST['SalesDate'],$_POST['CustomerID'],$_POST['GoodsID'],
            $_POST['Quantity'],$_POST['id']);
        var_dump($array);
        parent::executeSQL($sql,$array);
    }

    /**
     * 引数に取った値を更新、取得する
     * 取得できなかった場合はfalseを返す
     *
     * @param string $id
     * @param string $field
     * @return bool|PDOStatement
     */
    private function FieldValueForUpdate($id, $field){
        //引数の値を取得
        $sql = "SELECT {$field} FROM salesinfo WHERE id=?";
        $array = array($id);
        $res = parent::executeSQL($sql, $array);
        $rows = $res->fetch(PDO::FETCH_NUM);
        return $rows[0];
    }

    /**
     * 指定した売上情報の日付を更新、取得する
     * 取得できなかった場合はfalseを返す
     *
     * @param string $id
     * @return bool|PDOStatement
     */
    public function getSalesDate($id){
        return $this->FieldValueForUpdate($id, "SalesDate");
    }

    /**
     * 指定したIDの顧客情報を更新、取得する
     * 取得できなかった場合はfalseを返す
     *
     * @param string $id
     * @return bool|PDOStatement
     */
    public function getCustomerID($id){
        return $this->FieldValueForUpdate($id, "CustomerID");
    }

    /**
     * 商品IDを更新、取得する
     * 取得できなかった場合はfalseを返す
     *
     * @param string $id
     * @return bool|PDOStatement
     */
    public function getGoodsID($id){
        return $this->FieldValueForUpdate($id, "GoodsID");
    }

    /**
     * 指定した売上情報の数量を更新、取得する
     * 取得できなかった場合はfalseを返す
     *
     * @param string $id
     * @return bool|PDOStatement
     */
    public function getQuantity($id){
        return $this->FieldValueForUpdate($id, "Quantity");
    }

    /**
     * 商品名リストを作成する
     *
     * @param string $GoodsID
     * @return string
     */
    public function ListGoodsWithSelected($GoodsID){
        $sql = "SELECT GoodsID,GoodsName FROM goods ORDER BY GoodsID";
        $res = parent::executeSQL($sql,null);
        $list = "<select name='GoodsID'>\n";
        $list .= "<option value='-99'>--選択してください--</option>\n";
        foreach($rows=$res->fetchAll(PDO::FETCH_ASSOC) as $row){
            $selected = ($row['GoodsID'] == $GoodsID)?'selected':'';
            $list .= "<option value='{$row['GoodsID']}' {$selected}>{$row['GoodsName']}</option>\n";
        }
        $list .= "</select>\n";
        return $list;
    }

    /**
     * 日付と顧客IDで売上情報を抽出する
     *
     * @param string $salesDate
     * @param string $customerID
     * @return string
     */
    public function SelectSalesinfoWithButton($salesDate, $customerID){
        $res = $this->getSalesinfo($salesDate, $customerID);
        $data = "<table>\n";
        $data .= "<tr><th>ID</th><th>日付</th><th>顧客名</th><th>商品名</th>
              <th>単価</th><th>数量</th><th>金額</th><th></th><th></th></tr>\n";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM)as $row){
            $data .= "<tr>";
            for($i=0;$i<count($row);$i++){
                $data .= "<td>{$row[$i]}</td>";
            }
            $data .= <<<eof
      <td><form method='post' action=''>
      <input type='hidden' name='id' value='{$row[0]}'>
      <input type='submit' name='updatedetail' value='更新'></form></td>
      <td><form method='post' action=''>
      <input type='hidden' name='id' value='{$row[0]}'>
      <input type='submit' name='deletedetail' value='削除' onClick='return CheckDelete()'></form>
      </td></tr>\n
eof;
            $data .= "</tr>\n";
        }
        $data .= "</table>\n";
        return $data;
    }

    /**
     * 指定期間に該当する伝票を取得、合計金額を算出してstatementを返す
     * 取得できなかった場合はfalseを返す
     *
     * @param string $SalesDate
     * @param string $CustomerID
     * @return bool|PDOStatement
     */
    public function TotalAmount($SalesDate, $CustomerID){
        $sql = <<<eof
    SELECT sum(salesinfo.Quantity*goods.Price)
    FROM salesinfo INNER JOIN goods ON salesinfo.GoodsID = goods.GoodsID
    WHERE salesinfo.SalesDate = ? AND salesinfo.CustomerID = ?
eof;
        $array = array($SalesDate, $CustomerID);
        $res = parent::executeSQL($sql,$array);
        $row = $res->fetch(PDO::FETCH_NUM);
        return $row[0];
    }

    /**
     * 伝票を削除する
     *
     * @return void
     */
    public function DeleteSlip(){
        $sql = "DELETE FROM salesinfo WHERE SalesDate=? AND CustomerID=?";
        $array = array($_POST['SalesDate'],$_POST['CustomerID']);
        parent::executeSQL($sql,$array);
    }

    /**
     * 指定期間に該当する伝票を選択する
     *
     * @param string $salesDate
     * @return string
     */
    public function SelectSlips($salesDate){
        //日付で抽出
        $sql = <<<eof
    SELECT distinct salesinfo.SalesDate,salesinfo.CustomerID,customer.CustomerName, customer.CustomerID
    FROM salesinfo INNER JOIN customer ON salesinfo.CustomerID=customer.CustomerID
    WHERE salesinfo.SalesDate=?
    ORDER BY customer.CustomerID
eof;
        $array = array($salesDate);
        $res = parent::executeSQL($sql,$array);
        $data = "";
        foreach($rows = $res->fetchAll(PDO::FETCH_NUM)as $row){
            $data .= "<tr>";
            for($i=0;$i<count($row);$i++){
                $data .= "<td>{$row[$i]}</td>";
            }
            $data .= <<<eof
      <td><form method='post' action=''>
      <input type='hidden' name='SalesDate' value='{$row[0]}'>
      <input type='hidden' name='CustomerID' value='{$row[1]}'>
      <input type='submit' name='detail' value='詳細'></form></td>
      <td><form method='post' action=''>
      <input type='hidden' name='SalesDate' value='{$row[0]}'>
      <input type='hidden' name='CustomerID' value='{$row[1]}'>
      <input type='submit' name='delete' value='削除' onClick='return CheckDelete()'></form>
      </td></tr>\n
eof;
        }
        return $data;
    }
}
