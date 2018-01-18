<?php
require_once ('salesinfo.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>売上管理システム</title>
    <link rel="stylesheet" type="text/css" href="view/style.css" />
    <script type="text/javascript">
        function CheckDelete() {
            return confirm("削除してもよろしいですか？");
        }
    </script>
</head>
<body>
<div id = "menu">
    <ul>
        <li><a href="salesinfo.php">売上情報</a> </li>
        <li><a href="salesinfoEntry.php"> 伝票の新規作成</a> </li>
        <li><a href="bill.php">請求書</a> </li>
        <li><a href="customer.php"> 顧客マスタ</a> </li>
        <li><a href="goods.php">商品マスタ</a> </li>
    </ul>
</div>
<h1> 売上情報</h1>
<div id="search">
    <form method="post" action="">
        <label>日付<input type="date" id="SalesDate" name="SalesDate" value="<?php echo $SalesDate;?>" required></label>
        <input type="submit" value="    検索    " name="submit" />
        <input type="submit" value="←" id="prev" name="prev" />
        <input type="submit" value="→" id="next" name="next" />
    </form>
</div>
<div id = "sliplist">
    <table>
        <?php echo $slips;?>
    </table>
</div>
<div id="update" <?php echo $updateCss;?>>
    <form method="post" action="">
        <label>日付<input type="date" id = "SalesDate" name="SalesDate"
                        value="<?php echo $SalesDate;?>" required></label>
        <label> 顧客名<?php echo $CustomerList;?></label>
        <label> 商品名<?php echo $GoodsList;?></label>
        <label> 数量<input type="number" min ="0" id ="Quantity" name="Quantity"
                         value="<?php echo $Quantity;?>" required></label>
        <input type="hidden" name="id" value="<?php echo $id;?> "/>
        <input type="submit" value="更新" name="submit_updatedetail" />
    </form>
</div>
<div id="detail">
    <?php echo $slipDetail;?>
    <div id = "totalAmount">
        <?php echo $total;?>
    </div>
</div>
</body>
</html>
