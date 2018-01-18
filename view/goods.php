<?php
require_once ('goods.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>売上管理システム</title>
    <link rel="stylesheet" type="text/css" href="view/style.css" />
    <script type="text/javascript">
        function CheckDelete(){
            return confirm("削除してもよろしいですか？");
        }
    </script>
</head>
<body>
<div id="menu">
    <ul>
        <li><a href="salesinfo.php">売上情報</a></li>
        <li><a href="salesinfoEntry.php">伝票の新規作成</a></li>
        <li><a href="customer.php">顧客マスタ</a></li>
        <li><a href="goods.php">商品マスタ</a></li>
    </ul>
</div>
<h1>商品マスタ</h1>
<div id="entry" <?php echo $entryCss?>>
    <form action="" method="post">
        <h2>新規登録</h2>
        <lavel><span class="entrylabel">ID</span><input type="text" name="GoodsID" size="10" required></lavel>
        <lavel><span class="entrylabel">商品名</span><input type="text" name="GoodsName" size="30" required></lavel>
        <lavel><span class="entrylabel"> 単価</span> <input type="text" name="Price" size="10" required></lavel>
        <input type="submit" name="submitEntry" value="    新規登録    ">
    </form>
</div>
<div id="update" <?php echo $updateCss;?>>
    <form action="" method="post">
        <h2>更新</h2>
        <p>GoodsID:  <?php echo $dbGoodsId;?></p>
        <input type="hidden" name="GoodsID" value="<?php echo $dbGoodsId;?>" />
        <lavel><span class="entrylabel">商品名</span>
            <input type="text" name="GoodsName" size="30" value="<?php echo $dbGoodsName;?>" required></lavel>
        <lavel><span class="entrylabel">単価
            </span><input type="text" name="Price" size="10" value="<?php echo $Price;?>" required></lavel>
        <input type="submit" name="submitUpdate" value="    更新    ">
    </form>
</div>
<div>
    <?php echo $data; ?>
</div>
</body>
</html>
