<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ql_ocean4"; // Đảm bảo tên cơ sở dữ liệu đúng

try {
    // Kết nối MySQL bằng PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Kết nối thành công"; // Bỏ comment dòng này để kiểm tra kết nối
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM sanpham";  // Thay đổi tên bảng nếu cần
$stmt = $conn->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Lưu kết quả vào biến $products
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
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
            display: flex;
        }

        .left-panel {
            width: 100%;
            /* Phần bên trái chiếm 75% */
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            max-height: 100vh;
            overflow-y: auto;
        }

        .right-panel {
            width: 25%;
            /* Phần bên phải chiếm 25% */
            background: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            color: white;
            margin-left: 20px;
            overflow-y: auto;
            max-height: 70vh;
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            background: #333;
            color: white;
        }

        th,
        td {
            border: 1px solid white;
            padding: 10px;
        }

        th {
            background: #FFD700;
            color: black;
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
        textarea {
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

        a {
            color: #FFD700;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            #content {
                margin-left: 0;
                padding: 10px;
            }

            .menu {
                width: 100%;
                position: relative;
                margin-bottom: 20px;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <h2>Quản lý Sản phẩm</h2>

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
            <!-- Phần danh sách sản phẩm -->
            <div class="left-panel">
                <h2>Danh sách Sản phẩm</h2>
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá VIP 1A</th>
                        <th>Giá VIP 1</th>
                        <th>Giá VIP 2</th>
                        <th>SL 1</th>
                        <th>SL 2</th>
                        <th>SL 3-4</th>
                        <th>SL 5</th>
                        <th>SL 6-16</th>
                        <th>SL 16-50</th>
                        <th>SL 51-100</th>
                        <th>SL 101-200</th>
                        <th>SL 201-300</th>
                        <th>SL 301-400</th>
                        <th>SL 400-1000</th>
                        <th>Số lượng</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                    <?php foreach ($products as $row) { ?>

                        <tr>
                            <td><?= $row['SPID'] ?></td>
                            <td><?= $row['TenSP'] ?></td>
                            <td><?= number_format($row['gia_vip_1A'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_vip_1'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_vip_2'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl1'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl2'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl3_4'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl5'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl1_5'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl6_16'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl16_50'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl51_100'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl101_200'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl201_300'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl301_400'], 0, ',', '.') ?> đ</td>
                            <td><?= number_format($row['gia_sl400_1000'], 0, ',', '.') ?> đ</td>
                            <td><?= $row['SoLuong'] ?></td>
                            <td><?= $row['MoTa'] ?></td>
                            <td><a href="edit_product.php?id=<?= $row['SPID'] ?>">Sửa</a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <!-- <div>

                <h2>Thêm sản phẩm mới</h2>
                <form action="add_product.php" method="post">
                    <label>Tên sản phẩm:</label>
                    <input type="text" name="TenSP" required>

                    <label>Giá VIP 1:</label>
                    <input type="number" name="gia_vip1" required>

                    <label>Giá VIP 2:</label>
                    <input type="number" name="gia_vip2" required>

                    <label>Giá SL 1-5:</label>
                    <input type="number" name="gia_sl1_5" required>

                    <label>Giá SL 6-16:</label>
                    <input type="number" name="gia_sl6_16" required>

                    <label>Giá SL 16-50:</label>
                    <input type="number" name="gia_sl16_50" required>

                    <label>Giá SL 51-100:</label>
                    <input type="number" name="gia_sl51_100" required>

                    <label>Giá SL 101-200:</label>
                    <input type="number" name="gia_sl101_200" required>

                    <label>Giá SL 201-300:</label>
                    <input type="number" name="gia_sl201_300" required>

                    <label>Giá SL 301-400:</label>
                    <input type="number" name="gia_sl301_400" required>

                    <label>Giá SL 400-1000:</label>
                    <input type="number" name="gia_sl400_1000" required>

                    <label>Số lượng:</label>
                    <input type="number" name="SoLuong" required>

                    <label>Mô tả:</label>
                    <textarea name="MoTa" required></textarea>

                    <button type="submit">Thêm sản phẩm</button>
                </form>
            </div> -->

</body>

</html>