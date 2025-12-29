<?php 
//只能新增user表裡面有的學生活動內容
//當您新增繳費記錄時，您必須先知道這個 student_id 在 user 表中是否存在。
//在您的 payment_insert.php 中，所有與 name 相關的程式碼，都不是讓使用者填寫，而是讓伺服器 自動處理
$title = "新增繳費記錄";
require_once "header.php";

try {
    require_once 'db.php';
    $msg = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $student_id = $_POST["student_id"];
        $activity = $_POST["activity"];
        $amount = $_POST["amount"];
        $status = $_POST["status"];
        $payment_date = $_POST["payment_date"];
        $name_to_insert = ""; // 用於儲存從 user 表查詢到的姓名

        // 查詢 user 表，取得對應的 name
        $name_query_sql = "SELECT name FROM user WHERE student_id = ?";
        $stmt_check = mysqli_prepare($conn, $name_query_sql);
        mysqli_stmt_bind_param($stmt_check, "s", $student_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) == 0) { // 不存在在user表
            $user_insert_url = 'user_insert.php';
            $msg = '
            <div class="alert alert-warning m-3" role="alert">
                <strong>警告!</strong> 學號 <strong>' . htmlspecialchars($student_id) . '</strong> 不存在於用戶表中，請先新增該用戶。
                <hr>
                <p class="mb-0">
                    <a href="' . $user_insert_url . '" class="btn btn-sm btn-primary">
                        前往新增
                    </a>
                </p>
            </div>';

        } else {
            $user_row = mysqli_fetch_assoc($result_check); //將資料庫查詢結果（$result_check）中的第一筆記錄讀取出來，並存為一個以欄位名稱為索引的陣列 $user_row
            $name_to_insert = $user_row['name']; //從剛才提取的記錄中，取出 name 欄位的值，存入變數 $name_to_insert 中。
            
            $sql = "INSERT INTO payments (student_id, name, activity, amount, status, payment_date)
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_stmt_init($conn);
            
            if (mysqli_stmt_prepare($stmt, $sql)) {

                mysqli_stmt_bind_param($stmt, "sssiss", $student_id, $name_to_insert, $activity, $amount, $status, $payment_date);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    header('location:payment.php');
                    exit();
                } else {
                    $msg = "<p class='text-danger'>無法新增資料: " . mysqli_error($conn) . "</p>";
                }
                
                mysqli_stmt_close($stmt);
            } else {
                $msg = "<p class='text-danger'>資料庫發生錯誤。</p>";
            }
        }
        mysqli_stmt_close($stmt_check);
    }
?>

<div class="container mt-3 mb-3">
<h2>新增繳費記錄</h2>
<form action="payment_insert.php" method="post">

    <div class="mb-3 row">
      <label for="_student_id" class="col-sm-2 col-form-label">學號</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="student_id" id="_student_id" placeholder="學號" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_activity" class="col-sm-2 col-form-label">活動</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="activity" id="_activity" placeholder="活動" required>
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_amount" class="col-sm-2 col-form-label">金額</label>
      <div class="col-sm-10">
        <input type="number" class="form-control" name="amount" id="_amount" placeholder="金額" required>
      </div>
    </div>

    <div class="mb-3 row">
        <label for="_status" class="col-sm-2 col-form-label">繳費狀態</label>
        <div class="col-sm-10">
            <select id="_status" name="status" class="form-select" required>
                <option value="" disabled selected>請選擇一個狀態</option>
                <option value="已繳">已繳</option>
                <option value="未繳">未繳</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
      <label for="_payment_date" class="col-sm-2 col-form-label">日期</label>
      <div class="col-sm-10">
        <input type="date" class="form-control" name="payment_date" id="_payment_date" placeholder="日期" required>
      </div>
    </div>
    
    <input class="btn btn-primary" type="submit" value="送出">
    <a href="payment.php" class="btn btn-secondary">取消</a>
    <?=$msg?>
</form>
</div>

<?php
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>