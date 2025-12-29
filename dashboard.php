<link rel="stylesheet" href="dashboard.css" />
<?php 
$title = "財務儀表板";
require_once "header.php"; 
try{
    require_once "db.php"; 
// 金額顯示
    //總收入
    $total_income = 0;
    $sql_income_sum = "SELECT SUM(amount) AS total_income FROM income";
    $stmt_income_sum = mysqli_prepare($conn, $sql_income_sum);
    mysqli_stmt_execute($stmt_income_sum);
    $result_income_sum = mysqli_stmt_get_result($stmt_income_sum);

    if ($result_income_sum && $row = mysqli_fetch_assoc($result_income_sum)) { //做檢查 確保真的有值
        $total_income = $row['total_income'] ?? 0; //??不然就是 ㄉ意思
    }
    mysqli_stmt_close($stmt_income_sum);
    $formatted_income = number_format($total_income, 0);

    //總支出
    $total_expenses = 0;
    $sql_expenses_sum = "SELECT SUM(amount) AS total_expenses FROM expense";
    $stmt_expenses_sum = mysqli_prepare($conn, $sql_expenses_sum);
    mysqli_stmt_execute($stmt_expenses_sum);
    $result_expenses_sum = mysqli_stmt_get_result($stmt_expenses_sum);

    if ($result_expenses_sum && $row = mysqli_fetch_assoc($result_expenses_sum)) { //做檢查 確保真的有值
        $total_expenses = $row['total_expenses'] ?? 0; //??不然就是 ㄉ意思
    }
    mysqli_stmt_close($stmt_expenses_sum);
    $formatted_expenses = number_format($total_expenses, 0);

    //目前剩餘
    $current_amount =number_format($total_income-$total_expenses);

    //會費已繳總金額
    $total_paid = 0;
    $sql_paid_sum = "SELECT SUM(amount) AS total_paid FROM payments WHERE status = '已繳'";
    $stmt_paid_sum = mysqli_prepare($conn, $sql_paid_sum);
    mysqli_stmt_execute($stmt_paid_sum);
    $result_paid_sum = mysqli_stmt_get_result($stmt_paid_sum);

    if ($result_paid_sum && $row = mysqli_fetch_assoc($result_paid_sum)) { //做檢查 確保真的有值
        $total_paid = $row['total_paid'] ?? 0; //??不然就是 ㄉ意思
    }
    mysqli_stmt_close($stmt_paid_sum);
    $formatted_paid = number_format($total_paid, 0);

// 活動列表
    $sql_all_activities = "SELECT activity as all_activity FROM payments
        UNION
        SELECT name as all_activity FROM income
        UNION
        SELECT name as all_activity FROM expense
        ORDER BY all_activity ASC";
    $result_activities = mysqli_query($conn, $sql_all_activities);
    if (!$result_activities) {
        echo "查詢活動名稱時發生錯誤: " . mysqli_error($conn);
    }

    $stmt_income = mysqli_prepare($conn, "SELECT SUM(amount) AS each_income FROM income WHERE name = ?");
    $stmt_expenses = mysqli_prepare($conn, "SELECT SUM(amount) AS each_expenses FROM expense WHERE name = ?");
    $stmt_payments = mysqli_prepare($conn, "SELECT SUM(amount) AS each_paid FROM payments WHERE activity = ? AND status = '已繳'");

    $activity_data = []; 

    if ($result_activities && $stmt_income && $stmt_expenses && $stmt_payments) {
        while($row = mysqli_fetch_assoc($result_activities)) {
            $activity_name = $row["all_activity"];
            $income_amount = '';
            $expenses_amount = '';
            $paid_amount = '';

            // --- 收入 ---
            mysqli_stmt_bind_param($stmt_income, "s", $activity_name);
            mysqli_stmt_execute($stmt_income);
            $result_income = mysqli_stmt_get_result($stmt_income);
            $row_income = mysqli_fetch_assoc($result_income);
            $income_amount = $row_income['each_income'] ?? 0;
            mysqli_free_result($result_income); // 釋放結果集

            // --- 支出 ---
            mysqli_stmt_bind_param($stmt_expenses, "s", $activity_name);
            mysqli_stmt_execute($stmt_expenses);
            $result_expenses = mysqli_stmt_get_result($stmt_expenses);
            $row_expenses = mysqli_fetch_assoc($result_expenses);
            $expenses_amount = $row_expenses['each_expenses'] ?? 0;
            mysqli_free_result($result_expenses); // 釋放結果集

            // --- 已繳費用 ---
            mysqli_stmt_bind_param($stmt_payments, "s", $activity_name);
            mysqli_stmt_execute($stmt_payments);
            $result_payments = mysqli_stmt_get_result($stmt_payments);
            $row_payments = mysqli_fetch_assoc($result_payments);
            $paid_amount = $row_payments['each_paid'] ?? 0;
            mysqli_free_result($result_payments); // 釋放結果集
            
            // **將數據收集到陣列中**
            $activity_data[] = [
                'name' => $activity_name,
                'income' => number_format($income_amount, 0),
                'expenses' => number_format($expenses_amount, 0),
                'paid' => number_format($paid_amount, 0),
            ];

        } // 迴圈結束
        mysqli_free_result($result_activities); // 釋放活動列表結果集
    } else {
        // 檢查是否所有準備都成功
        if (!$stmt_income || !$stmt_expenses || !$stmt_payments) {
            echo "準備查詢語句時發生錯誤: " . mysqli_error($conn);
        }
    }
    mysqli_stmt_close($stmt_income);
    mysqli_stmt_close($stmt_expenses);
    mysqli_stmt_close($stmt_payments);
}
catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}

?>
<div class="container mt-4 mb-3">
    <div class="container position-relative">
        <a href="fileupload.php" class="btn btn-primary position-absolute" style="top: 1rem; right: 1rem;">上傳活動宣傳照</a>
    </div>
    <div>
        <h2>學生會活動收支儀表板</h2>
        <div class="subtitle mb-2">管理所有收支紀錄</div>
    </div>

    <div class="row">
        <div class="col-md-3 total-box1">
            <div class="total-label1">總收入</div>
            <div class="total-amount1">NT$ <?php echo $formatted_income; ?></div>
        </div>
        <div class="col-md-3 total-box2">
            <div class="total-label2">總支出</div>
            <div class="total-amount2">NT$ <?php echo $formatted_expenses; ?></div>
        </div>
        <div class="col-md-3 total-box3">
            <div class="total-label3">目前餘額</div>
            <div class="total-amount3">NT$ <?php echo $current_amount; ?></div>
        </div>
        <div class="col-md-3 total-box4">
            <div class="total-label4">會費收入</div>
            <div class="total-amount4">NT$ <?php echo $formatted_paid; ?></div>
        </div>
    </div>
</div>

<div class="container mt-3 mb-3">
    <div class="row">
        <div class="col-md-6"><?php require_once "e_chart.php"; ?></div>

        <div class="col-md-6"><?php require_once "i_bar.php"; ?></div>
    </div>
</div>

<div class="container card mb-2">
    <h5 class="mt-3 mb-3">總活動收支一覽表</h5>
<table class=" table table-bordered table-striped">
    <tr>
        <th>活動名稱</th>
        <th>收入</th>
        <th>支出</th>
        <th>已繳費用</th>
    </tr>
    <?php foreach ($activity_data as $activity): ?>
    <tr>
        <td><?=htmlspecialchars($activity['name'])?></td>
        <td><?=$activity['income']?></td>
        <td><?=$activity['expenses']?></td>
        <td><?=$activity['paid']?></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php 
require_once "footer.php"; 
?>