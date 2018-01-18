<?php
require('model/DBSalesInfo.php');

$slipDetail = "";
$total = "";
$SalesDate = new DateTime('NOW');
$SalesDate = $SalesDate->format("Y-m-d");
$dbSalesInfo = new DBSalesInfo();
$CustomerList = "";
$GoodsList = "";
//日付の設定
if (isset($_POST['SalesDate'])) {
    $SalesDate = $_POST['SalesDate'];
}

//ボタンの処理（一日前を表示）
if (isset($_POST['prev'])) {
    $SalesDate = new DateTime($_POST['SalesDate']);
    //日付の減算処理はsubメソッド
    $SalesDate->sub(new DateInterval('P1D'));
    $SalesDate = $SalesDate -> format("Y-m-d");
}

//ボタンの処理（1日後を表示）
if (isset($_POST['next'])) {
    $SalesDate = new DateTime($_POST['SalesDate']);
    //日付の加算はaddメソッド
    $SalesDate->add(new DateInterval('P1D'));
    $SalesDate = $SalesDate -> format("Y-m-d");
}

//伝票明細の削除
if (isset($_POST['deletedetail'])) {
    $SalesDate = $dbSalesInfo->getSalesDate($_POST['id']);
    $dbSalesInfo->DeleteDetail();
}

//明細行の更新
if (isset($_POST['submit_updatedetail'])) {
    $SalesDate = $_POST['SalesDate'];
    $dbSalesInfo->UpdateDetail();
}

//明細行の更新ボタンの処理（更新用フォームの表示とデータの設定）
$updateCss = "class='hideArea'";
if (isset($_POST{'updatedetail'})) {
    //フォーム要素の仕込み
    $updateCss = "";
    $id = $_POST['id'];
    $SalesDate = $dbSalesInfo->getSalesDate($id);
    $CustomerID = $dbSalesInfo->getCustomerID($id);
    $CustomerList = $dbSalesInfo->ListCustomerWithSelected($CustomerID);
    $GoodsID = $dbSalesInfo->getGoodsID($id);
    $GoodsList = $dbSalesInfo->ListGoodsWithSelected($GoodsID);
    $Quantity = $dbSalesInfo->getQuantity($id);
}

//詳細ボタンの処理（日付と顧客IDで伝票を抽出して合計額を表示）
if (isset($_POST['detail'])) {
    $SalesDate = $_POST['SalesDate'];
    $CustomerID = $_POST['CustomerID'];
    $slipDetail = $dbSalesInfo->SelectSalesinfoWithButton($SalesDate,$CustomerID);
    $total = $dbSalesInfo->TotalAmount($SalesDate, $CustomerID); //伝票の合計額
    //number_format関数は3桁区切りの文字列を返す
    $total =($total==null)?"" :"合計金額：" .number_format($total) ."円";
}

//伝票の削除
if (isset($_POST['delete'])) {
    $dbSalesInfo->DeleteSlip();
}

// 指定日の伝票一覧
$slips = $dbSalesInfo->SelectSlips($SalesDate);
if ($slips == "") {
    $slips = "<tr><td>伝票はありません</td></tr>\n";
}
include "view/salesInfo.php";
