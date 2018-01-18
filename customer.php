<?php
require_once('model/DBCustomer.php');

$dbCustomer = new DBCustomer();

//更新処理
if (isset($_POST['submitUpdate'])) {
    $dbCustomer->UpdateCustomer();
}

//更新用フォーム要素の表示
if (isset($_POST['update'])) {
    //更新対象の値を取得
    $dbCustomerId   = $_POST['id'];
    $dbCustomerName = $dbCustomer->CustomerNameForUpdate($_POST['id']);
    $tel            = $dbCustomer->TELForUpdate($_POST['id']);
    $email          = $dbCustomer->EmailForUpdate($_POST['id']);
    //クラスを記述することで表示/非表示を設定
    $entryCss = "class='hideArea'";
    $updateCss = "";
} else {
    $entryCss = "";
    $updateCss = "class='hideArea'";
}

//削除処理
if (isset($_POST['delete'])) {
    $dbCustomer->DeleteCustomer($_POST['id']);
}

//新規登録処理
if (isset($_POST['submitEntry'])) {
    $dbCustomer->InsertCustomer();
}

//全レコードの表示
$data = $dbCustomer->SelectCustomerAll();
include "view/customer.php";
