<?php
include 'db_connect.php';

// Lấy danh sách khách hàng
$query = "
    SELECT kh.KHID, kh.Ten, kh.Email, kh.SDT, kh.DiaChi, ctkh.NgaySinh, ctkh.GioiTinh, ctkh.GhiChu
    FROM khachhang kh
    LEFT JOIN chitietkhachhang ctkh ON kh.KHID = ctkh.KHID
";
$stmt = $conn->prepare($query);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách hàng</title>
    <style>
        /* Layout */
        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .left-panel {
            width: 75%;
        }

        .right-panel {
            width: 20%;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            color: white;
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

        /* Form */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            color: #FFD700;
        }

        input, select, textarea {
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

<div class="container">
    <!-- Phần danh sách khách hàng -->
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
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= $customer['KHID'] ?></td>
                <td><?= $customer['Ten'] ?></td>
                <td><?= $customer['Email'] ?></td>
                <td><?= $customer['SDT'] ?></td>
                <td><?= $customer['DiaChi'] ?></td>
                <td><?= $customer['NgaySinh'] ?></td>
                <td><?= $customer['GioiTinh'] ?></td>
                <td><?= $customer['GhiChu'] ?></td>
                <td><a href="edit_customer.php?id=<?= $customer['KHID'] ?>">Sửa</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Phần thêm khách hàng -->
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

</body>
</html>
