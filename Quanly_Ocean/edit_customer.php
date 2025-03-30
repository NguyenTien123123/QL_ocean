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

    // Kiểm tra nếu người dùng đã gửi form
    // Xử lý khi người dùng gửi form sửa khách hàng
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ten = $_POST['Ten'];
        $email = $_POST['Email'];
        $sdt = $_POST['SDT'];
        $diachi = $_POST['DiaChi'];
        $ngaysinh = $_POST['NgaySinh'];
        $gioitinh = $_POST['GioiTinh'];
        $ghichu = $_POST['GhiChu'];

        $query = "UPDATE khachhang SET Ten = :Ten, Email = :Email, SDT = :SDT, DiaChi = :DiaChi, NgaySinh = :NgaySinh, GioiTinh = :GioiTinh, GhiChu = :GhiChu WHERE KHID = :KHID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':Ten', $ten);
        $stmt->bindParam(':Email', $email);
        $stmt->bindParam(':SDT', $sdt);
        $stmt->bindParam(':DiaChi', $diachi);
        $stmt->bindParam(':NgaySinh', $ngaysinh);
        $stmt->bindParam(':GioiTinh', $gioitinh);
        $stmt->bindParam(':GhiChu', $ghichu);
        $stmt->bindParam(':KHID', $id);

        if ($stmt->execute()) {
            echo '<script>
                alert("Cập nhật khách hàng thành công!");
                window.location.href = "quanly_khachhang.php";
            </script>';
            exit();
        } else {
            echo '<script>alert("Lỗi khi cập nhật khách hàng!");</script>';
        }
    }
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

        input,
        textarea,
        select {
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

        button:hover {
            background: #ffb300;
        }
    </style>
</head>

<body>

    <h2>Quản lý Khách Hàng</h2>

    <div class="container">
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

        <div id="content">
            <h2>Sửa Thông Tin Khách Hàng</h2>
            <form method="POST">
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