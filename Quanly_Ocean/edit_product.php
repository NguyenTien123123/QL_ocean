<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy ID sản phẩm từ URL
$SPID = isset($_GET['id']) ? $_GET['id'] : 0;

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM sanpham WHERE SPID = :SPID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':SPID', $SPID);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu sản phẩm không tồn tại
if (!$product) {
    echo "Sản phẩm không tồn tại!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản phẩm</title>
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

        input, textarea {
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
            <h2>Sửa Sản Phẩm</h2>
            <form method="POST" action="update_product.php">
                <!-- Lưu ID sản phẩm trong trường hidden -->
                <input type="hidden" name="SPID" value="<?= $product['SPID'] ?>">

                <label for="TenSP">Tên sản phẩm:</label>
                <input type="text" id="TenSP" name="TenSP" value="<?= $product['TenSP'] ?>" required>

                <label for="Gia">Giá:</label>
                <input type="number" id="Gia" name="Gia" value="<?= $product['Gia'] ?>" required>

                <label for="SoLuong">Số lượng:</label>
                <input type="number" id="SoLuong" name="SoLuong" value="<?= $product['SoLuong'] ?>" required>

                <label for="MoTa">Mô tả:</label>
                <textarea id="MoTa" name="MoTa" required><?= $product['MoTa'] ?></textarea>

                <button type="submit">Cập nhật</button>
            </form>
        </div>
    </div>

</body>
</html>
