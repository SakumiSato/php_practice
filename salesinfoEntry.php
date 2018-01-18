<?php
require('model/DBSalesInfo.php');
$slip = "";
$SalesDate = new DateTime('NOW');
$SalesDate = $SalesDate->format('Y-m-d');
$CustomerID = "";
$dbSalesInfo = new DbSalesInfo();
//商品名リストの作成
$GoodsList = $dbSalesInfo->ListGoods();
if (isset($_POST['submit'])) {
    //新規登録処理
    $SalesDate = $_POST['SalesDate'];
    $CustomerID = $_POST['CustomerID'];
    $dbSalesInfo->InsertSalesinfo();
    //新規登録後だけ登録データを表示
    $slip = $dbSalesInfo->SelectSalesinfo($SalesDate, $CustomerID);
    //顧客名リストの作成（選択者を表示）
    $CustomerList = $dbSalesInfo->ListCustomerWithSelected($CustomerID);
} else {
    //顧客名リストの作成
    $CustomerList = $dbSalesInfo->ListCustomer();
}
include "view/entry.php";