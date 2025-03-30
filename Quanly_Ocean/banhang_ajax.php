<?php
include 'db_connect.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu yêu cầu POST từ form thêm đơn bán hàng
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    // Lấy dữ liệu từ form
    $nhanvien = $_POST['nhanvien'];
    $ngayban = $_POST['ngayban'];
    $tongtien = $_POST['tongtien'];
    $khachhang = $_POST['khachhang'];

    // Thực hiện truy vấn thêm đơn bán hàng
    $query = "INSERT INTO donhang (NVID, KHID, NgayBan, TongTien) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$nhanvien, $khachhang, $ngayban, $tongtien]);

    // Kiểm tra xem có thành công không
    if ($stmt->rowCount() > 0) {
        echo "Đơn bán hàng đã được thêm thành công!";
    } else {
        echo "Có lỗi xảy ra khi thêm đơn bán hàng.";
    }
}
?>
