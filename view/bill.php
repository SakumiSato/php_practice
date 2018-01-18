<?php
require_once ('bill.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>売上管理システム</title>
    <link rel="stylesheet" type="text/css" href="view/style.css" />
</head>
<body>
<div id="menu">
    <ul>
        <li><a href="salesinfo.php">売上情報</a></li>
        <li><a href="salesinfoEntry.php">伝票の新規作成</a></li>
        <li><a href="customer.php">顧客マスタ</a></li>
        <li><a href="goods.php">商品マスタ</a></li>
        <li><a href="bill.php">請求書</a></li>
    </ul>
</div>
<h1>請求情報</h1>
<div id="search">
    <form method="post" action="">
        <label>請求期間<input type="date" id="startDate" name = "startDate" value="<?php echo $startDate;?>" required></label>
        <label> 〜 <input type="date" id ="endDate" name="endDate" value="<?php echo $endDate;?>" required></label>
        <?php echo $TagCustomer;?>
        <input type="submit" value="    検索    " name="submit" />
    </form>
</div>
<div id="detail">
    <p><?php echo $customerName;?></p>
    <div id="totalAmount">
        <?php echo $total;?>
    </div>
    <?php echo $detail;?>
</div>
</body>
</html>