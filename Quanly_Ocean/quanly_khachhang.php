<?php
include 'db_connect.php';

// Kiểm tra nếu có thông báo thành công từ URL
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script type='text/javascript'>alert('Thêm khách hàng thành công!');</script>";
}

// Lấy danh sách khách hàng từ cơ sở dữ liệu
$query = "SELECT * FROM khachhang";  // Thay đổi tên bảng nếu cần
$stmt = $conn->prepare($query);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Lưu kết quả vào biến $customers
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khách hàng</title>
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

        th, td {
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
        <div class="left-panel">
            <h2>Quản lý Khách Hàng</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Tên khách hàng</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
                <?php if ($customers): ?>
                    <?php foreach ($customers as $row) { ?>
                    <tr>
                        <td><?= $row['KHID'] ?></td>
                        <td><?= $row['Ten'] ?></td>
                        <td><?= $row['Email'] ?></td>
                        <td><?= $row['SDT'] ?></td>
                        <td><?= $row['DiaChi'] ?></td>
                        <td><?= $row['NgaySinh'] ?></td>
                        <td><?= $row['GioiTinh'] ?></td>
                        <td><?= $row['GhiChu'] ?></td>
                        <td><a href="edit_customer.php?id=<?= $row['KHID'] ?>">Sửa</a></td>
                    </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Không có khách hàng nào!</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="right-panel">
            <h2>Thêm Khách Hàng Mới</h2>
            <form method="POST" action="add_customer.php">
                <label for="Ten">Tên khách hàng:</label>
                <input type="text" id="Ten" name="Ten" required>

                <label for="Email">Email:</label>
                <input type="email" id="Email" name="Email" required>

                <label for="SDT">Số điện thoại:</label>
                <input type="text" id="SDT" name="SDT" required>

                <label for="DiaChi">Địa chỉ:</label>
                <textarea id="DiaChi" name="DiaChi" required></textarea>

                <label for="NgaySinh">Ngày sinh:</label>
                <input type="date" id="NgaySinh" name="NgaySinh">

                <label for="GioiTinh">Giới tính:</label>
                <select id="GioiTinh" name="GioiTinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>

                <label for="GhiChu">Ghi chú:</label>
                <textarea id="GhiChu" name="GhiChu"></textarea>

                <button type="submit">Thêm khách hàng</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
