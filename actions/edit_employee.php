<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is admin
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $ma_nv = sanitize($_POST['ma_nv']);
    $ten_nv = sanitize($_POST['ten_nv']);
    $phai = sanitize($_POST['phai']);
    $noi_sinh = sanitize($_POST['noi_sinh']);
    $ma_phong = sanitize($_POST['ma_phong']);
    $luong = (int)$_POST['luong'];
    
    // Validate data
    if (empty($ma_nv) || empty($ten_nv) || empty($ma_phong)) {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin bắt buộc';
        header('Location: ../index.php');
        exit;
    }
    
    // Update employee
    $stmt = $conn->prepare("UPDATE nhanvien SET Ten_NV = ?, Phai = ?, Noi_Sinh = ?, Ma_Phong = ?, Luong = ? WHERE Ma_NV = ?");
    $stmt->bind_param("ssssss", $ten_nv, $phai, $noi_sinh, $ma_phong, $luong, $ma_nv);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Cập nhật thông tin nhân viên thành công';
    } else {
        $_SESSION['error'] = 'Lỗi: ' . $stmt->error;
    }
    
    $stmt->close();
}

header('Location: ../index.php');
exit;

