<?php
require_once('db.php');
class DBBill extends DB
{
    //Bill.phpを担当するクラス
    /**
     * 指定期間に存在する顧客一覧の結果セットを取得
     * 取得できなかった場合falseを返す
     *
     * @param string $startDate
     * @param string $endDate
     * @return bool|PDOStatement
     */
    private function SelectCustomers($startDate, $endDate)
    {
        $sql = <<<eof
        select distinct salesinfo.CustomerID, customer.CustomerName
        from salesinfo INNER JOIN customer ON salesinfo.CustomerID=Customer.CustomerID
        WHERE salesinfo.SalesDate BETWEEN ? AND ?
        ORDER BY salesinfo.CustomerID
eof;
        $array = array($startDate, $endDate);
        $res = parent::executeSQL($sql, $array);
        return $res;
    }

    /**
     *選択した期間内に請求書が存在する顧客を検索する
     *
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public  function SelectTagOfCustomers($startDate, $endDate)
    {
        $rows = $this->SelectCustomers($startDate, $endDate)->fetchAll(PDO::FETCH_NUM);
        if (count($rows) == 0) return "";
        $tag = "<select name='CustomerID' id='CustomerID'>\n";
        $tag .= "<option value ='-99'>--    選択してください  --</option>\n";
        foreach ($rows as $row){
            $tag .= "<option value = '{$row[0]}'>{$row[1]}</option>\n";
        }
        $tag .= "</select>\n";
        return $tag;
    }

    /**
     *顧客IDより顧客名を取得する
     *
     * @param string $CustomerID
     * @return array
     */
    public function GetCustomerName($CustomerID)
    {
        $sql = "select CustomerName from customer WHERE  CustomerID=?";
        $array = array($CustomerID);
        $res = parent::executeSQL($sql, $array);
        $row = $res->fetch(PDO::FETCH_NUM);
        return $row[0];
    }

    /**
     *対象の顧客ID、請求期間に該当する請求書の合計金額を算出
     *
     * @param string $startDate
     * @param string $endDate
     * @param  string $CustomerID
     * @return array
     */
    public function TotalAmount($startDate, $endDate, $CustomerID)
    {
        //請求書の合計額
        $sql = <<<eof
        select sum(salesinfo.Quantity*goods.Price)
        from salesinfo INNER JOIN goods ON salesinfo.GoodsID = goods.GoodsID
        WHERE (salesinfo.SalesDate BETWEEN ? AND ?)AND salesinfo.CustomerID=?
eof;
        $array = array($startDate, $endDate, $CustomerID);
        $res = parent::executeSQL($sql, $array);
        $row = $res->fetch(PDO::FETCH_NUM);
        return $row[0];
    }

    /**
     *指定した期間、顧客の請求情報を取得する
     * 取得できなかった場合falseを返す
     *
     * @param string $startDate
     * @param string $endDate
     * @param  string $CustomerID
     * @return bool|PDOStatement
     */
    private function getSalesinfo($startDate, $endDate, $CustomerID)
    {
        $sql = <<<eof
        select salesinfo.id, salesinfo.SalesDate, salesinfo.GoodsID, goods.GoodsName, 
                          goods.Price, salesinfo.Quantity, (goods.Price*salesinfo.Quantity)
        from salesinfo INNER JOIN goods ON salesinfo.GoodsID=goods.GoodsID
        WHERE salesinfo.SalesDate BETWEEN ? AND ?
        AND salesinfo.CustomerID=?
        ORDER BY salesinfo.SalesDate, salesinfo.id
eof;
        $array = array($startDate, $endDate, $CustomerID);
        $res = parent::executeSQL($sql, $array);
        return $res;
    }

    /**
     *指定した期間、顧客の請求情報を取得して表示するためのhtmlを作成
     *
     * @param string $startDate
     * @param string $endDate
     * @param string $CustomerID
     * @return string
     */
    public function SelectSalesinfo($startDate, $endDate, $CustomerID)
    {
        //$fieldCount = 7;
        $tag = "<table>\n";
        $tag .= "<tr><th>ID</th><th>日付</th><th>顧客名</th><th>商品名</th><th>単価</th><th>数量</th><th>金額</th><th></th><th></th></tr>\n";
        $res = $this->getSalesinfo($startDate, $endDate, $CustomerID);
        foreach ($rows = $res->fetchAll(PDO::FETCH_NUM) as $row) {
            $tag .= "<tr>";
            //次の行のcount関数の引数は$rows[0]にすること
            for ($i=0; $i<count($rows[0]); $i++) {
                $tag .= "<td>{$row[$i]}</td>";
            }
            $tag .= "</tr>\n";
        }
        $tag .= "</table>\n";
        return $tag;
    }
}
