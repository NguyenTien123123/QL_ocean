<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy danh sách nhà cung cấp
$query = "SELECT * FROM Nhacungcap";
$stmt = $conn->prepare($query);
$stmt->execute();
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhà Cung Cấp</title>
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
            width: 70%;
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }

        .right-panel {
            width: 25%;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            color: white;
            margin-left: 20px;
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
    </style>
</head>

<body>

    <h2>Quản lý Nhà Cung Cấp</h2>

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
            <div class="left-panel">
                <h2>Danh sách Nhà Cung Cấp</h2>

                <!-- Hiển thị thông báo nếu thêm nhà cung cấp thành công -->
                <?php
                if (isset($_GET['added']) && $_GET['added'] == 'true') {
                    echo "<script>alert('Thêm nhà cung cấp thành công!');</script>";
                }
                ?>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Địa chỉ</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Hành động</th>
                    </tr>
                    <?php if ($suppliers): ?>
                        <?php foreach ($suppliers as $row) { ?>
                            <tr>
                                <td><?= $row['NCCID'] ?></td>
                                <td><?= $row['TenNCC'] ?></td>
                                <td><?= $row['DiaChi'] ?></td>
                                <td><?= $row['SDT'] ?></td>
                                <td><?= $row['Email'] ?></td>
                                <td><?= $row['Website'] ?></td>
                                <td><a href="edit_supplier.php?id=<?= $row['NCCID'] ?>">Sửa</a></td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Không có nhà cung cấp nào!</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>

            <div class="right-panel">
                <h2>Thêm Nhà Cung Cấp Mới</h2>
                <form method="POST" action="add_supplier.php">
                    <label for="TenNCC">Tên nhà cung cấp:</label>
                    <input type="text" id="TenNCC" name="TenNCC" required>

                    <label for="DiaChi">Địa chỉ:</label>
                    <input type="text" id="DiaChi" name="DiaChi" required>

                    <label for="SDT">Số điện thoại:</label>
                    <input type="text" id="SDT" name="SDT" required>

                    <label for="Email">Email:</label>
                    <input type="email" id="Email" name="Email" required>

                    <label for="Website">Website:</label>
                    <input type="text" id="Website" name="Website" required>

                    <button type="submit">Thêm nhà cung cấp</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>