<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is admin
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ma_nv'])) {
    $ma_nv = sanitize($_POST['ma_nv']);
    
    // Delete employee
    $stmt = $conn->prepare("DELETE FROM nhanvien WHERE Ma_NV = ?");
    $stmt->bind_param("s", $ma_nv);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Xóa nhân viên thành công';
    } else {
        $_SESSION['error'] = 'Lỗi: ' . $stmt->error;
    }
    
    $stmt->close();
}

header('Location: ../index.php');
exit;

