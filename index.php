<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['role'] === 'admin';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Default page is employee list
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Get total number of employees for pagination
$totalQuery = "SELECT COUNT(*) as total FROM nhanvien";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalEmployees = $totalRow['total'];
$totalPages = ceil($totalEmployees / $limit);

// Get employees with department name
$query = "SELECT nv.*, pb.Ten_Phong 
          FROM nhanvien nv 
          LEFT JOIN phongban pb ON nv.Ma_Phong = pb.Ma_Phong 
          LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Get all departments for forms
$deptQuery = "SELECT * FROM phongban";
$deptResult = $conn->query($deptQuery);
$departments = [];
while ($row = $deptResult->fetch_assoc()) {
    $departments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhân Sự</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-4">
        <header class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Quản Lý Nhân Sự</h1>
                <div>
                    <?php if ($isLoggedIn): ?>
                        <span class="me-2">
                            Xin chào, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                            (<?php echo $_SESSION['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>)
                        </span>
                        <a href="?logout=1" class="btn btn-outline-danger">Đăng xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <?php if ($isAdmin): ?>
            <div class="mb-4">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-plus"></i> Thêm nhân viên
                </button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Danh sách nhân viên</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Mã NV</th>
                                <th>Họ tên</th>
                                <th>Giới tính</th>
                                <th>Nơi sinh</th>
                                <th>Phòng ban</th>
                                <th>Lương</th>
                                <?php if ($isAdmin): ?>
                                    <th>Thao tác</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['Ma_NV']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Ten_NV']); ?></td>
                                        <td>
                                            <?php if ($row['Phai'] === 'NAM'): ?>
                                                <img src="assets/images/man.jpg" alt="Nam" width="30" height="30" class="rounded-circle">
                                                Nam
                                            <?php else: ?>
                                                <img src="assets/images/woman.jpg" alt="Nữ" width="30" height="30" class="rounded-circle">
                                                Nữ
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['Noi_Sinh']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Ten_Phong']); ?></td>
                                        <td><?php echo number_format($row['Luong']); ?></td>
                                        <?php if ($isAdmin): ?>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning edit-btn" 
                                                        data-bs-toggle="modal" data-bs-target="#editEmployeeModal"
                                                        data-id="<?php echo $row['Ma_NV']; ?>"
                                                        data-name="<?php echo htmlspecialchars($row['Ten_NV']); ?>"
                                                        data-gender="<?php echo $row['Phai']; ?>"
                                                        data-birthplace="<?php echo htmlspecialchars($row['Noi_Sinh']); ?>"
                                                        data-dept="<?php echo $row['Ma_Phong']; ?>"
                                                        data-salary="<?php echo $row['Luong']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal"
                                                        data-id="<?php echo $row['Ma_NV']; ?>"
                                                        data-name="<?php echo htmlspecialchars($row['Ten_NV']); ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?php echo $isAdmin ? 7 : 6; ?>" class="text-center">Không có nhân viên nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($isAdmin): ?>
        <!-- Add Employee Modal -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Thêm nhân viên mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="actions/add_employee.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="ma_nv" class="form-label">Mã nhân viên</label>
                                <input type="text" class="form-control" id="ma_nv" name="ma_nv" required maxlength="3">
                            </div>
                            <div class="mb-3">
                                <label for="ten_nv" class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="ten_nv" name="ten_nv" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giới tính</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="phai" id="gender_nam" value="NAM" checked>
                                        <label class="form-check-label" for="gender_nam">Nam</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="phai" id="gender_nu" value="NU">
                                        <label class="form-check-label" for="gender_nu">Nữ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="noi_sinh" class="form-label">Nơi sinh</label>
                                <input type="text" class="form-control" id="noi_sinh" name="noi_sinh">
                            </div>
                            <div class="mb-3">
                                <label for="ma_phong" class="form-label">Phòng ban</label>
                                <select class="form-select" id="ma_phong" name="ma_phong" required>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo $dept['Ma_Phong']; ?>">
                                            <?php echo htmlspecialchars($dept['Ten_Phong']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="luong" class="form-label">Lương</label>
                                <input type="number" class="form-control" id="luong" name="luong" required min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-success">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Employee Modal -->
        <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Sửa thông tin nhân viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="actions/edit_employee.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="edit_ma_nv" name="ma_nv">
                            <div class="mb-3">
                                <label for="edit_ten_nv" class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="edit_ten_nv" name="ten_nv" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giới tính</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="phai" id="edit_gender_nam" value="NAM">
                                        <label class="form-check-label" for="edit_gender_nam">Nam</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="phai" id="edit_gender_nu" value="NU">
                                        <label class="form-check-label" for="edit_gender_nu">Nữ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_noi_sinh" class="form-label">Nơi sinh</label>
                                <input type="text" class="form-control" id="edit_noi_sinh" name="noi_sinh">
                            </div>
                            <div class="mb-3">
                                <label for="edit_ma_phong" class="form-label">Phòng ban</label>
                                <select class="form-select" id="edit_ma_phong" name="ma_phong" required>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo $dept['Ma_Phong']; ?>">
                                            <?php echo htmlspecialchars($dept['Ten_Phong']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_luong" class="form-label">Lương</label>
                                <input type="number" class="form-control" id="edit_luong" name="luong" required min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-warning">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Employee Modal -->
        <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEmployeeModalLabel">Xác nhận xóa nhân viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa nhân viên <span id="delete_employee_name" class="fw-bold"></span>?</p>
                        <p class="text-danger">Hành động này không thể hoàn tác!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="actions/delete_employee.php" method="post">
                            <input type="hidden" id="delete_ma_nv" name="ma_nv">
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>

