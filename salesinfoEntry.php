<?php
require('model/DBSalesInfo.php');
$slip = "";
$SalesDate = new DateTime('NOW');
$SalesDate = $SalesDate->format('Y-m-d');
$CustomerID = "";
$dbSalesInfo = new DbSalesInfo();
//商品名リストの作成
$GoodsList = $dbSalesInfo->ListGoods();

include "view/entry.php";