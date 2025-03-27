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
    
    // Check if employee ID already exists
    $checkStmt = $conn->prepare("SELECT Ma_NV FROM nhanvien WHERE Ma_NV = ?");
    $checkStmt->bind_param("s", $ma_nv);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = 'Mã nhân viên đã tồn tại';
        header('Location: ../index.php');
        exit;
    }
    $checkStmt->close();
    
    // Insert new employee
    $stmt = $conn->prepare("INSERT INTO nhanvien (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $ma_nv, $ten_nv, $phai, $noi_sinh, $ma_phong, $luong);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Thêm nhân viên thành công';
    } else {
        $_SESSION['error'] = 'Lỗi: ' . $stmt->error;
    }
    
    $stmt->close();
}

header('Location: ../index.php');
exit;

