<?php
require_once('model/DBBill.php');
$startDate = "";
$endDate = "";
$TagCustomer = "";
$customerName = "";
$detail = "";
$total = "";

$dbBill = new DBBill();

include "view/bill.php";
