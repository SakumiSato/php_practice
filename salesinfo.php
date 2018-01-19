<?php
require('model/DBSalesInfo.php');

$slipDetail = "";
$total = "";
$SalesDate = new DateTime('NOW');
$SalesDate = $SalesDate->format("Y-m-d");
$dbSalesInfo = new DBSalesInfo();
$CustomerList = "";
$GoodsList = "";

include "view/salesInfo.php";