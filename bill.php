<?php
require_once('model/DBBill.php');
$startDate = "";
$endDate = "";
$TagCustomer = "";
$customerName = "";
$detail = "";
$total = "";

$dbBill = new DBBill();
if (isset($_POST['submit'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $TagCustomer = $dbBill->SelectTagOfCustomers($startDate, $endDate);
    if (isset($_POST['CustomerID'])) {
        $customerName = $dbBill->GetCustomerName($_POST['CustomerID']);
        $total =  $dbBill->TotalAmount($startDate, $endDate, $_POST['CustomerID']);
        $total = ($total==null)? "" :"合計金額：" .number_format((int)$total) ."円";
        $detail = $dbBill->SelectSalesinfo($startDate, $endDate, $_POST['CustomerID']);
    }
}
include "view/bill.php";
