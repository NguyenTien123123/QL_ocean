<?php
include 'db_connect.php'; // Kết nối CSDL

// Kiểm tra nếu có yêu cầu 'action' là 'add'
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $nhanvien = $_POST['nhanvien'];
    $ngaynhap = $_POST['ngaynhap'];
    $tongtien = $_POST['tongtien'];

    // Kiểm tra các giá trị không rỗng
    if (!empty($nhanvien) && !empty($ngaynhap) && isset($tongtien)) {
        // Thêm đơn nhập hàng vào cơ sở dữ liệu
        $query = "INSERT INTO nhaphang (NVID, NgayNhap, TongTien) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        // Kiểm tra nếu thao tác thành công
        if ($stmt->execute([$nhanvien, $ngaynhap, $tongtien])) {
            echo "Đơn nhập hàng đã được thêm thành công!";
        } else {
            echo "Có lỗi xảy ra khi thêm đơn nhập hàng.";
        }
    } else {
        echo "Vui lòng điền đầy đủ thông tin!";
    }
}
?>
