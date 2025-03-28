<?php
include 'db_connect.php';

// Kiểm tra nếu có tham số 'id' trong URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin khách hàng từ cơ sở dữ liệu
    $query = "SELECT * FROM khachhang WHERE KHID = :KHID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':KHID', $id);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        echo "Khách hàng không tồn tại!";
        exit();
    }
}

// Cập nhật thông tin khách hàng khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = $_POST['Ten'];
    $email = $_POST['Email'];
    $sdt = $_POST['SDT'];
    $diachi = $_POST['DiaChi'];
    $ngaysinh = $_POST['NgaySinh'];
    $gioitinh = $_POST['GioiTinh'];
    $ghichu = $_POST['GhiChu'];

    // Kiểm tra định dạng ngày sinh (YYYY-MM-DD)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $ngaysinh)) {
        echo "Ngày sinh không hợp lệ!";
        exit();
    }

    // Kiểm tra giới tính hợp lệ
    if (!in_array($gioitinh, ['Nam', 'Nữ', 'Khác'])) {
        echo "Giới tính không hợp lệ!";
        exit();
    }

    // Cập nhật vào bảng khách hàng
    $query = "
        UPDATE khachhang 
        SET Ten = ?, Email = ?, SDT = ?, DiaChi = ?, NgaySinh = ?, GioiTinh = ?, GhiChu = ?
        WHERE KHID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ten, $email, $sdt, $diachi, $ngaysinh, $gioitinh, $ghichu, $id]);

    // Lưu thông báo thành công vào session
    session_start();
    $_SESSION['success_message'] = "Cập nhật khách hàng thành công!";

    // Chuyển hướng về trang quản lý khách hàng
    header("Location: quanly_khachhang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Khách Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #ffcc00;
            text-align: center;
            margin-top: 20px;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .menu {
            width: 250px;
            background-color: #222;
            padding-top: 20px;
            text-align: left;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            overflow-y: auto;
            border-right: 2px solid #ffcc00;
        }

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin: 10px 0;
        }

        .menu a {
            text-decoration: none;
            color: #ffcc00;
            font-size: 18px;
            padding: 10px;
            display: block;
            text-align: center;
            border: 2px solid #ffcc00;
            border-radius: 5px;
            transition: 0.3s;
        }

        .menu a:hover {
            background-color: #ffcc00;
            color: #1e1e1e;
        }

        .form-container {
            margin-left: 270px;
            padding: 40px;
            width: 80%;
        }

        .form-container form {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
        }

        .form-container label {
            font-weight: bold;
            color: #ffcc00;
        }

        .form-container input, .form-container textarea, .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #444;
            color: white;
            border: 1px solid #ffcc00;
            border-radius: 5px;
        }

        .form-container button {
            background-color: #ffcc00;
            color: #1e1e1e;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
        }

        .form-container button:hover {
            background-color: #ffb300;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Menu sidebar -->
        <div class="menu">
            <ul>
            <li><a href="quanly_nhaphang.php">Quản lý nhập hàng</a></li>
                <li><a href="quanly_banhang.php">Quản lý bán hàng</a></li>
                <li><a href="quanly_sanpham.php">Quản lý sản phẩm</a></li>
                <li><a href="quanly_khachhang.php">Quản lý khách hàng</a></li>
                <li><a href="quanly_nhanvien.php">Quản lý nhân viên</a></li>
                <li><a href="quanly_nhacungcap.php">Quản lý nhà cung cấp</a></li>
                <li><a href="thongke_doanhthu_nvnv.php">Báo cáo doanh thu theo nhân viên</a></li>
                <li><a href="thongke_doanhthu_sp.php">Báo cáo doanh thu theo sản phẩm</a></li>
            </ul>
        </div>

        <!-- Form sửa khách hàng -->
        <div class="form-container">
            <h2>Sửa Thông Tin Khách Hàng</h2>
            <form method="POST" action="">
                <label for="Ten">Tên khách hàng:</label>
                <input type="text" id="Ten" name="Ten" value="<?= $customer['Ten'] ?>" required>

                <label for="Email">Email:</label>
                <input type="email" id="Email" name="Email" value="<?= $customer['Email'] ?>" required>

                <label for="SDT">Số điện thoại:</label>
                <input type="text" id="SDT" name="SDT" value="<?= $customer['SDT'] ?>" required>

                <label for="DiaChi">Địa chỉ:</label>
                <textarea id="DiaChi" name="DiaChi" required><?= $customer['DiaChi'] ?></textarea>

                <label for="NgaySinh">Ngày sinh:</label>
                <input type="date" id="NgaySinh" name="NgaySinh" value="<?= $customer['NgaySinh'] ?>">

                <label for="GioiTinh">Giới tính:</label>
                <select id="GioiTinh" name="GioiTinh">
                    <option value="Nam" <?= ($customer['GioiTinh'] == 'Nam') ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= ($customer['GioiTinh'] == 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                    <option value="Khác" <?= ($customer['GioiTinh'] == 'Khác') ? 'selected' : '' ?>>Khác</option>
                </select>

                <label for="GhiChu">Ghi chú:</label>
                <textarea id="GhiChu" name="GhiChu"><?= $customer['GhiChu'] ?></textarea>

                <button type="submit">Cập nhật thông tin</button>
            </form>
        </div>
    </div>

</body>
</html>
