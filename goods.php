<?php
require_once('model/DBGoods.php');
$dbGoods = new DBGoods();
// 更新処理
if (isset($_POST['submitUpdate'])) {
    $dbGoods->UpdateGoods();
}

//更新用フォームの表示
if (isset($_POST['update'])) {
    // 更新対象の値を取得
    $dbGoodsId = $_POST['id'];
    $dbGoodsName = $dbGoods->GoodsNameForUpdate($_POST['id']);
    $Price = $dbGoods->PriceForUpdate($_POST['id']);
    //クラスを記述することで表示/非表示を設定
    $entryCss = "class='hideArea'";
    $updateCss = "";
} else {
    $entryCss = "";
    $updateCss = "class = 'hideArea'";
}

//削除処理
if (isset($_POST['delete'])) {
    $dbGoods->DeleteGoods($_POST['id']);
}

//新規登録処理
if (isset($_POST['submitEntry'])) {
    $dbGoods->InsertGoods();
}

//テーブルのデータの一覧表示
$data = $dbGoods->SelectGoodsAll();
include "view/goods.php";