<?php
include 'db_connect.php';

if (!isset($_GET['khid'])) {
    die("Khách hàng không tồn tại.");
}

$khid = $_GET['khid'];

// Lấy thông tin khách hàng
$query = "SELECT * FROM khachhang WHERE KHID = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$khid]);
$khachhang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$khachhang) {
    die("Khách hàng không tồn tại.");
}

// Lấy thông tin chi tiết khách hàng
$query = "SELECT * FROM chitietkhachhang WHERE KHID = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$khid]);
$chitiet = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Chi tiết khách hàng</h2>
<p><strong>Tên:</strong> <?= $khachhang['Ten'] ?></p>
<p><strong>Email:</strong> <?= $khachhang['Email'] ?></p>
<p><strong>SĐT:</strong> <?= $khachhang['SDT'] ?></p>
<p><strong>Địa chỉ:</strong> <?= $khachhang['DiaChi'] ?></p>

<?php if ($chitiet) { ?>
    <p><strong>Ngày sinh:</strong> <?= $chitiet['NgaySinh'] ?></p>
    <p><strong>Giới tính:</strong> <?= $chitiet['GioiTinh'] ?></p>
    <p><strong>Ghi chú:</strong> <?= $chitiet['GhiChu'] ?></p>
<?php } else { ?>
    <p>Chưa có thông tin chi tiết.</p>
<?php } ?>
