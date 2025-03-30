<?php
include 'db_connect.php'; // Kết nối CSDL

// Lấy ID đơn hàng từ URL
$dhid = isset($_GET['dhid']) ? $_GET['dhid'] : 0;

// Truy vấn chi tiết đơn hàng
$query = "
    SELECT dh.DHID, dh.NVID, dh.KHID, dh.NgayBan, dh.TongTien, k.Ten AS KhachHang
    FROM donhang dh
    LEFT JOIN khachhang k ON dh.KHID = k.KHID
    WHERE dh.DHID = ?
";


$stmt = $conn->prepare($query);
$stmt->execute([$dhid]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu đơn hàng không tồn tại
if (!$order) {
    echo "Không tìm thấy đơn hàng.";
    exit;
}

// Lấy chi tiết sản phẩm trong đơn hàng
$queryDetails = "
    SELECT ct.SPID, s.TenSP, ct.SoLuong, ct.Gia
    FROM chitietdonhang ct
    LEFT JOIN sanpham s ON ct.SPID = s.SPID
    WHERE ct.DHID = ?
";
$stmtDetails = $conn->prepare($queryDetails);
$stmtDetails->execute([$dhid]);
$orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
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

        .form-panel {
            width: 98%;
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }

        .product-panel {
            width: 98%;
            background-color: #444;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            color: white;
            margin-bottom: 20px;
        }

        .product-panel h4 {
            text-align: center;
            color: #FFD700;
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

        .back-btn {
            background-color: #ffcc00;
            padding: 10px 20px;
            border: none;
            color: black;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            text-align: center;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #ff9900;
        }
    </style>
</head>

<body>

    <h2>Chi tiết đơn hàng</h2>

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
            <!-- Phần thông tin đơn hàng -->
            <div class="form-panel">
                <h3>Thông tin đơn hàng</h3>
                <p><strong>ID Đơn hàng:</strong> <?= $order['DHID'] ?></p>
                <p><strong>Khách hàng:</strong> <?= $order['KhachHang'] ?></p>
                <p><strong>Ngày bán:</strong> <?= $order['NgayBan'] ?></p>
                <p><strong>Tổng tiền:</strong> <?= number_format($order['TongTien'], 2) ?> VNĐ</p>
            </div>

            <!-- Phần danh sách sản phẩm trong đơn hàng -->
            <div class="product-panel">
                <h4>Danh sách sản phẩm trong đơn hàng</h4>
                <table>
                    <tr>
                        <th>ID Sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                    </tr>
                    <?php foreach ($orderDetails as $item) { ?>
                        <tr>
                            <td><?= $item['SPID'] ?></td>
                            <td><?= $item['TenSP'] ?></td>
                            <td><?= $item['SoLuong'] ?></td>
                            <td><?= number_format($item['Gia'], 2) ?> VNĐ</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <a href="quanly_banhang.php" class="back-btn">Quay lại</a>
        </div>
    </div>

</body>

</html>