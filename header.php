<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current = basename($_SERVER['PHP_SELF']);
function nav_active($file) {
    global $current;
    return $current === $file ? ' active' : '';
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>系學會活動收支管理系統</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--在可視區域裡寬度是容器大小初始縮放比例是1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="header.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">活動報名系統</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= nav_active('dashboard.php') ?>" href="dashboard.php">財務儀表板</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= nav_active('income.php') ?>" href="income.php">收入管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= nav_active('expenses.php') ?>" href="expenses.php">支出管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= nav_active('payment.php') ?>" href="payment.php">收費管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= nav_active('activities.php') ?>" href="activities.php">活動管理</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
</body>