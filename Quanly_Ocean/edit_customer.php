<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

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
        SET Ten = ?, Email = ?, SDT = ?, DiaChi = ?
        WHERE KHID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ten, $email, $sdt, $diachi, $id]);

    // Cập nhật vào bảng chi tiết khách hàng
    $query = "
        UPDATE chitietkhachhang 
        SET NgaySinh = ?, GioiTinh = ?, GhiChu = ?
        WHERE KHID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ngaysinh, $gioitinh, $ghichu, $id]);

    // Chuyển hướng về trang quản lý khách hàng sau khi cập nhật
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

        #content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            background-color: #333;
            border-radius: 10px;
            border: 2px solid #ffcc00;
            height: calc(100vh - 40px);
            color: #fff;
            overflow-y: auto;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            color: #FFD700;
        }

        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            background: #333;
            color: white;
            border: 1px solid #FFD700;
            border-radius: 5px;
        }

        button {
            margin-top: 15px;
            background: #FFD700;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h2>Hệ thống quản lý</h2>

    <div class="container">
        <div class="menu">
            <ul>
                <li><a href="quanly_nhaphang.php">Quản lý nhập hàng</a></li>
                <li><a href="quanly_banhang.php">Quản lý bán hàng</a></li>
                <li><a href="quanly_sanpham.php">Quản lý sản phẩm</a></li>
                <li><a href="quanly_khachhang.php">Quản lý khách hàng</a></li>
                <li><a href="quanly_nhanvien.php">Quản lý nhân viên</a></li>
                <li><a href="quanly_nhacungcap.php">Quản lý nhà cung cấp</a></li>
            </ul>
        </div>

        <div id="content">
            <h2>Sửa Khách Hàng</h2>
            <form method="POST" action="edit_customer.php?id=<?= $customer['KHID'] ?>">
                <!-- Lưu ID khách hàng trong trường hidden -->
                <input type="hidden" name="KHID" value="<?= $customer['KHID'] ?>">

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
                    <option value="Nam" <?= $customer['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= $customer['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                    <option value="Khác" <?= $customer['GioiTinh'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
                </select>

                <label for="GhiChu">Ghi chú:</label>
                <textarea id="GhiChu" name="GhiChu"><?= $customer['GhiChu'] ?></textarea>

                <button type="submit">Cập nhật khách hàng</button>
            </form>
        </div>
    </div>

</body>
</html>
