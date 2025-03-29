<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy danh sách nhân viên
$query = "SELECT * FROM nhanvien";
$stmt = $conn->prepare($query);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Lấy dữ liệu và gán vào biến $employees

// Xử lý khi người dùng gửi form thêm nhân viên mới
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['Ten'];
    $email = $_POST['Email'];
    $sdt = $_POST['SDT'];
    $chucvu = $_POST['ChucVu'];

    $query = "INSERT INTO nhanvien (Ten, Email, SDT, ChucVu) VALUES (:Ten, :Email, :SDT, :ChucVu)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':Ten', $ten);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':SDT', $sdt);
    $stmt->bindParam(':ChucVu', $chucvu);

    if ($stmt->execute()) {
        // Sau khi thêm thành công, chuyển hướng về trang quanly_nhanvien.php với thông báo 'added=true'
        echo '<script>
                alert("Thêm nhân viên thành công!");
                window.location.href="quanly_nhanvien.php?added=true";
              </script>';
    } else {
        echo '<script>alert("Lỗi khi thêm nhân viên!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhân viên</title>
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
            /* Điều chỉnh phần này chiếm 70% */
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }

        .right-panel {
            width: 25%;
            /* Điều chỉnh phần này chiếm 25% */
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
            <!-- Phần danh sách nhân viên -->
            <div class="left-panel">
                <h2>Quản lý Nhân viên</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Tên nhân viên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Chức vụ</th>
                        <th>Hành động</th>
                    </tr>
                    <?php if ($employees): ?>
                        <?php foreach ($employees as $row) { ?>
                            <tr>
                                <td><?= $row['NVID'] ?></td>
                                <td><?= $row['Ten'] ?></td>
                                <td><?= $row['Email'] ?></td>
                                <td><?= $row['SDT'] ?></td>
                                <td><?= $row['ChucVu'] ?></td>
                                <td><a href="edit_employee.php?id=<?= $row['NVID'] ?>">Sửa</a></td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Không có nhân viên nào!</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Phần thêm nhân viên -->
            <div class="right-panel">
                <h2>Thêm Nhân Viên Mới</h2>
                <form method="POST" action="quanly_nhanvien.php">
                    <label for="Ten">Tên nhân viên:</label>
                    <input type="text" id="Ten" name="Ten" required>

                    <label for="Email">Email:</label>
                    <input type="email" id="Email" name="Email" required>

                    <label for="SDT">Số điện thoại:</label>
                    <input type="text" id="SDT" name="SDT" required>

                    <label for="ChucVu">Chức vụ:</label>
                    <input type="text" id="ChucVu" name="ChucVu" required>

                    <button type="submit">Thêm nhân viên</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>