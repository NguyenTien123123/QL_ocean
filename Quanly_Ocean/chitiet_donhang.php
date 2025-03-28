<?php
include 'db_connect.php'; // Kết nối CSDL

// Lấy ID đơn hàng từ URL
$dhid = isset($_GET['dhid']) ? $_GET['dhid'] : 0;

// Truy vấn chi tiết đơn hàng
$query = "
    SELECT dh.DHID, dh.NVID, dh.KhachHang, dh.NgayBan, dh.TongTien, k.Ten AS KhachHang
    FROM donhang dh
    LEFT JOIN khachhang k ON dh.KhachHang = k.KHID
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

<h2>Chi tiết đơn hàng</h2>
<p><strong>ID Đơn hàng:</strong> <?= $order['DHID'] ?></p>
<p><strong>Khách hàng:</strong> <?= $order['KhachHang'] ?></p>
<p><strong>Ngày bán:</strong> <?= $order['NgayBan'] ?></p>
<p><strong>Tổng tiền:</strong> <?= number_format($order['TongTien'], 2) ?> VNĐ</p>

<h3>Chi tiết sản phẩm trong đơn hàng</h3>
<table border="1" style="width:100%; text-align:center;">
    <tr>
        <th>ID Sản phẩm</th>
        <th>Tên sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá</th>
    </tr>
    <?php foreach ($orderDetails as $detail) { ?>
    <tr>
        <td><?= $detail['SPID'] ?></td>
        <td><?= $detail['TenSP'] ?></td>
        <td><?= $detail['SoLuong'] ?></td>
        <td><?= number_format($detail['Gia'], 2) ?> VNĐ</td>
    </tr>
    <?php } ?>
</table>
