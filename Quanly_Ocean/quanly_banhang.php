<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy danh sách đơn hàng với thông tin khách hàng
$query = "
    SELECT dh.DHID, dh.NVID, dh.KHID, dh.NgayBan, dh.TongTien, k.Ten AS TenKH
    FROM donhang dh
    JOIN khachhang k ON dh.KHID = k.KHID
";
$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 10px;
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            white-space: nowrap;
        }

        input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        form {
            margin-bottom: 20px;
        }

        button {
            padding: 5px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Quản lý Đơn hàng</h2>

<!-- Bảng hiển thị đơn hàng -->
<table>
    <tr>
        <th>ID Đơn hàng</th>
        <th>ID Nhân viên</th>
        <th>ID Khách hàng</th>
        <th>Tên Khách hàng</th>
        <th>Ngày bán</th>
        <th>Tổng tiền</th>
    </tr>
    <?php foreach ($orders as $order) { ?>
    <tr>
        <td><?= $order['DHID'] ?></td>
        <td><?= $order['NVID'] ?></td>
        <td><?= $order['KHID'] ?></td>
        <td><?= $order['TenKH'] ?></td>
        <td><?= $order['NgayBan'] ?></td>
        <td><?= number_format($order['TongTien'], 2) ?> VNĐ</td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
