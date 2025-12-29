
<?php
require_once "header.php";
?>
<?php
try {
  require_once 'db.php';
  $totalSql = "SELECT SUM(amount) as total FROM income";
    $totalResult = mysqli_query($conn, $totalSql);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $total = $totalRow['total'] ?? 0;

  $sql="SELECT * from income";
  $result = mysqli_query($conn, $sql);
  
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="income.css" />
    <title>收入管理</title>
</head>
<body>
    <div class="container">
    <div class="header">
            <div>
                <h1>收入管理</h1>
                <div class="subtitle">管理所有收入紀錄</div>
            </div>
        </div>
        
        <div class="total-box">
            <div class="total-label">總收入</div>
            <div class="total-amount">NT$ <?php echo number_format($total); ?></div>
        </div>

        <form action="income_insert.php" method="GET" style="display: inline;">
            <button type="submit" class="addbtn">
            + 新增收入
            </button>
        </form>

        <table class="table table-bordered table-striped">
            <tr>
                <th>活動名稱</th>
                <th>類別</th>
                <th>金額</th>
                <th>日期</th>
                <th>說明</th>
                <th>操作</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)) {?>
                <tr>
                    <th><?=$row["name"]?></th>
                    <th><span class="category-badge category-<?=$row["category"]?>">
                        <?=$row["category"]?>
                    </th>
                    <th><?=$row["amount"]?></th>
                    <th><?=$row["date"]?></th>
                    <th><?=$row["description"]?></th>
                </tr>
            <?php } ?>
        </table>
    </div>


<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}

require "footer.php";
?>
</body>