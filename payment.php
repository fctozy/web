<link rel="stylesheet" href="payment.css" />
<?php
$title = "收費管理";
require_once "header.php";

$order = $_POST["order"] ?? "";  //排序
$searchtxt = $_POST["searchtxt"] ?? ""; //查詢

//順序
$allowed_orders = ["student_id","name","activity","amount","payment_date"];
$orderByClause = "payment_date desc";

if (in_array($order, $allowed_orders)) {
  $orderByClause = 'p.' . $order;
}

try {
    require_once 'db.php';

    // ============================計算已繳和未繳總金額==============================
    $total_paid = 0;
    $total_unpaid = 0;

    //已繳總金額
    $sql_paid_sum = "SELECT SUM(amount) AS total_paid FROM payments WHERE status = '已繳'";
    $stmt_paid_sum = mysqli_prepare($conn, $sql_paid_sum);
    mysqli_stmt_execute($stmt_paid_sum);
    $result_paid_sum = mysqli_stmt_get_result($stmt_paid_sum);

    if ($result_paid_sum && $row = mysqli_fetch_assoc($result_paid_sum)) { //做檢查 確保真的有值
        $total_paid = $row['total_paid'] ?? 0; //??不然就是 ㄉ意思
    }
    mysqli_stmt_close($stmt_paid_sum);

    //未繳總金額
    $sql_unpaid_sum = "SELECT SUM(amount) AS total_unpaid FROM payments WHERE status = '未繳'";
    $stmt_unpaid_sum = mysqli_prepare($conn, $sql_unpaid_sum);
    mysqli_stmt_execute($stmt_unpaid_sum);
    $result_unpaid_sum = mysqli_stmt_get_result($stmt_unpaid_sum);
    
    if ($result_unpaid_sum && $row = mysqli_fetch_assoc($result_unpaid_sum)) {
        $total_unpaid = $row['total_unpaid'] ?? 0;
    }
    mysqli_stmt_close($stmt_unpaid_sum);
    
    // 格式化
    $formatted_paid = number_format($total_paid, 0);
    $formatted_unpaid = number_format($total_unpaid, 0);
    $formatted_all = number_format($total_paid + $total_unpaid, 0);


    // ============================查詢與排序==============================
    $sql = "SELECT p.payment_id, p.student_id, p.name, p.activity, p.amount, p.status, p.payment_date
            FROM payments p";

    //Prepared Statements 查詢
    $whereClause = "";
    $params = [];
    $types = "";

    if (!empty($searchtxt)) {
        $searchTerm = "%" . $searchtxt . "%";
        $whereClause .= " WHERE (p.student_id LIKE ? OR p.activity LIKE ? OR p.name LIKE ?)";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "sss";
    }

    //排序+查詢
    $sql .= $whereClause . " ORDER BY " . $orderByClause;
    $stmt = mysqli_prepare($conn, $sql);

    // 查詢 準備
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    // 查詢 執行
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


?>

<div class="container mt-4 mb-3">
        <div>
            <h2>繳費管理</h2>
            <div class="subtitle">管理所有繳費紀錄</div>
        </div>

    <div class="row">
        <div class="col-md-4 total-box1">
            <div class="total-label1">已繳總額</div>
            <div class="total-amount1">NT$ <?php echo $formatted_paid; ?></div>
        </div>
        <div class="col-md-4 total-box2">
            <div class="total-label2">未繳總額</div>
            <div class="total-amount2">NT$ <?php echo $formatted_unpaid; ?></div>
        </div>
        <div class="col-md-4 total-box3">
            <div class="total-label3">全部總額</div>
            <div class="total-amount3">NT$ <?php echo $formatted_all; ?></div>
        </div>
    </div>
</div>

<div class=" container mt-3 mb-3">
  <form action="" method="post" class="mb-3">
      <div class="row g-2 m-3">
          <div class="col-md-3">
              <select name="order" class="form-select" aria-label=" ">
                  <option value="" <?= ($order == '') ? 'selected' : '' ?>>預設排序 (日期最新)</option>
                  <option value="student_id" <?= ($order == "student_id") ? "selected" : "" ?>>依學生ID排序</option>
                  <option value="amount" <?= ($order == "amount") ? "selected" : "" ?>>依金額排序</option>
                  <option value="activity" <?= ($order == "activity") ? "selected" : "" ?>>依活動內容排序</option>
                  <option value="payment_date" <?= ($order == "payment_date") ? "selected" : "" ?>>依繳費日期排序</option>
              </select>
          </div>

          <div class="col-md-7">
              <input placeholder="搜尋學生ID、姓名及活動內容" type="text" name="searchtxt" class="form-control" value="<?= htmlspecialchars($searchtxt) ?>">
          </div>

          <div class="col-md-1">
              <input class="btn btn-primary w-100" type="submit" value="搜尋">
          </div>

          <div class="col-md-1 position-relative">
            <a href="payment_insert.php" class="btn btn-primary" >+</a>
          </div>
      </div>
  </form>

<table class="table table-bordered table-striped">
    <tr>
        <td>學號</td>
        <td>姓名</td>
        <td>活動</td>
        <td>金額</td>
        <td>狀態</td>
        <td>繳費日期</td>
        <td>操作</td>
    </tr>

    <?php
    while($row = mysqli_fetch_assoc($result)) {?>
    <tr>
        <td><?=$row["student_id"]?></td>
        <td><?=$row["name"]?></td>
        <td><?=$row["activity"]?></td>
        <td><?=number_format($row["amount"], 0)?></td>
        <td><span class="category-badge category-<?=$row["status"]?>"><?=$row["status"]?></td>
        <td><?=$row["payment_date"]?></td>
        <td>
        <a href="payment_update.php?payment_id=<?=$row["payment_id"]?>" class="btn btn-primary">✎</a>
        <a href="payment_delete.php?payment_id=<?=$row['payment_id']?>" class="btn btn-danger">🗑</a></td>
    </tr>
    <?php
     }
    ?>
</table>

</div>
<?php
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
//catch exception
catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}

require_once "footer.php";
?>
