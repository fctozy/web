<link rel="stylesheet" href="payment.css" />
<?php
$title = "學生管理";
require_once "header.php";

$order = $_POST["order"] ?? "";  //排序
$searchtxt = $_POST["searchtxt"] ?? ""; //查詢

//順序
$allowed_orders = ["student_id","grade","payment_count"];
$orderByClause = "student_id desc";

if (in_array($order, $allowed_orders)) {
  $orderByClause = 'u.' . $order.' ASC';
}

try {
    require_once 'db.php';

    $sql = "SELECT u.student_id, u.name, u.grade, u.member_status, u.payment_count
            FROM user u";

    //Prepared Statements 查詢
    $whereClause = "";
    $params = [];
    $types = "";

    if (!empty($searchtxt)) {
        $searchTerm = "%" . $searchtxt . "%";
        $whereClause .= " WHERE (u.student_id LIKE ? OR u.name LIKE ? OR u.grade LIKE ? OR u.member_status LIKE ?)";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ssss";
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
<div class=" card container mt-3 mb-3">
<h3 class='m-2'>已登記學生列表</h3>
  <form action="" method="post" class="mb-3">
      <div class="row g-2 m-3">
          <div class="col-md-3">
              <select name="order" class="form-select" aria-label=" ">
                  <option value="" <?= ($order == '') ? 'selected' : '' ?>>預設排序 (學生ID)</option>
                  <option value="grade" <?= ($order == "grade") ? "selected" : "" ?>>依系級排序</option>
                  <option value="payment_count" <?= ($order == "payment_count") ? "selected" : "" ?>>依繳費次數排序</option>
              </select>
          </div>

          <div class="col-md-7">
              <input placeholder="搜尋學生ID、姓名及系級" type="text" name="searchtxt" class="form-control" value="<?= htmlspecialchars($searchtxt) ?>">
          </div>

          <div class="col-md-1">
              <input class="btn btn-primary w-100" type="submit" value="搜尋">
          </div>

          <div class="col-md-1 position-relative">
            <a href="user_insert.php" class="btn btn-primary position-absolute" >+</a>
          </div>
      </div>
  </form>

<table class="table table-bordered table-striped">
    <tr>
        <td>學號</td>
        <td>姓名</td>
        <td>系級</td>
        <td>會員</td>
        <td>操作</td>
    </tr>

    <?php
    while($row = mysqli_fetch_assoc($result)) {?>
    <tr>
        <td><?=$row["student_id"]?></td>
        <td><?=$row["name"]?></td>
        <td><?=$row["grade"]?></td>
        <td><span class="category-badge category-<?=$row["member_status"]?>"><?=$row["member_status"]?></td>
        <td>
        <a href="user_update.php?student_id=<?=$row["student_id"]?>" class="btn btn-primary">✎</a></td>
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
