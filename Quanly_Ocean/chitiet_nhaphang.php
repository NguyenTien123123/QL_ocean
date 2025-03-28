<?php
include 'db_connect.php'; // Kết nối CSDL

// Lấy ID nhập hàng từ URL
$nhid = isset($_GET['nhid']) ? $_GET['nhid'] : 0;

// Truy vấn chi tiết nhập hàng
$query = "
    SELECT nh.NHID, nh.NVID, nh.NgayNhap, nh.TongTien, n.TenNCC AS NhaCungCap
    FROM nhaphang nh
    LEFT JOIN nhacungcap n ON nh.NVID = n.NCCID
    WHERE nh.NHID = ?
";
$stmt = $conn->prepare($query);
$stmt->execute([$nhid]);
$import = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu đơn nhập hàng không tồn tại
if (!$import) {
    echo "Không tìm thấy đơn nhập hàng.";
    exit;
}

// Lấy chi tiết sản phẩm trong nhập hàng
$queryDetails = "
    SELECT ct.SPID, s.TenSP, ct.SoLuong, ct.Gia
    FROM chitietnhaphang ct
    LEFT JOIN sanpham s ON ct.SPID = s.SPID
    WHERE ct.NHID = ?
";
$stmtDetails = $conn->prepare($queryDetails);
$stmtDetails->execute([$nhid]);
$importDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Chi tiết nhập hàng</h2>
<p><strong>ID Nhập hàng:</strong> <?= $import['NHID'] ?></p>
<p><strong>Nhà cung cấp:</strong> <?= $import['NhaCungCap'] ?></p>
<p><strong>Ngày nhập:</strong> <?= $import['NgayNhap'] ?></p>
<p><strong>Tổng tiền:</strong> <?= number_format($import['TongTien'], 2) ?> VNĐ</p>

<h3>Chi tiết sản phẩm trong nhập hàng</h3>
<table border="1" style="width:100%; text-align:center;">
    <tr>
        <th>ID Sản phẩm</th>
        <th>Tên sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá</th>
    </tr>
    <?php foreach ($importDetails as $detail) { ?>
    <tr>
        <td><?= $detail['SPID'] ?></td>
        <td><?= $detail['TenSP'] ?></td>
        <td><?= $detail['SoLuong'] ?></td>
        <td><?= number_format($detail['Gia'], 2) ?> VNĐ</td>
    </tr>
    <?php } ?>
</table>
