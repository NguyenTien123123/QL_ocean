<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Kiểm tra nếu có tham số 'id' trong URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin nhà cung cấp từ cơ sở dữ liệu
    $query = "SELECT * FROM Nhacungcap WHERE NCCID = :NCCID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':NCCID', $id);
    $stmt->execute();
    $supplier = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$supplier) {
        echo "Nhà cung cấp không tồn tại!";
        exit();
    }

    // Xử lý khi form được gửi
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Lấy dữ liệu từ form
        $tenNCC = $_POST['TenNCC'];
        $diaChi = $_POST['DiaChi'];
        $sdt = $_POST['SDT'];
        $email = $_POST['Email'];
        $website = $_POST['Website'];

        // Cập nhật thông tin nhà cung cấp
        $updateQuery = "UPDATE Nhacungcap SET TenNCC = :TenNCC, DiaChi = :DiaChi, SDT = :SDT, Email = :Email, Website = :Website WHERE NCCID = :NCCID";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bindParam(':TenNCC', $tenNCC);
        $stmtUpdate->bindParam(':DiaChi', $diaChi);
        $stmtUpdate->bindParam(':SDT', $sdt);
        $stmtUpdate->bindParam(':Email', $email);
        $stmtUpdate->bindParam(':Website', $website);
        $stmtUpdate->bindParam(':NCCID', $id);

        // Thực hiện cập nhật
        if ($stmtUpdate->execute()) {
            echo "<script>alert('Cập nhật nhà cung cấp thành công!'); window.location.href='quanly_nhacungcap.php';</script>";
        } else {
            echo "<script>alert('Cập nhật nhà cung cấp thất bại!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Nhà Cung Cấp</title>
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

        input {
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
                <li><a href="thongke_doanhthu_nvnv.php">Báo cáo doanh thu theo nhân viên</a></li>
                <li><a href="thongke_doanhthu_sp.php">Báo cáo doanh thu theo sản phẩm</a></li>
            </ul>
        </div>

        <div id="content">
            <h2>Sửa Nhà Cung Cấp</h2>
            <form method="POST">
                <label for="TenNCC">Tên nhà cung cấp:</label>
                <input type="text" id="TenNCC" name="TenNCC" value="<?= $supplier['TenNCC'] ?>" required>

                <label for="DiaChi">Địa chỉ:</label>
                <input type="text" id="DiaChi" name="DiaChi" value="<?= $supplier['DiaChi'] ?>" required>

                <label for="SDT">Số điện thoại:</label>
                <input type="text" id="SDT" name="SDT" value="<?= $supplier['SDT'] ?>" required>

                <label for="Email">Email:</label>
                <input type="email" id="Email" name="Email" value="<?= $supplier['Email'] ?>" required>

                <label for="Website">Website:</label>
                <input type="text" id="Website" name="Website" value="<?= $supplier['Website'] ?>" required>

                <button type="submit">Cập nhật nhà cung cấp</button>
            </form>
        </div>
    </div>

</body>
</html>
