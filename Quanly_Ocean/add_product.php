<?php
include 'db_connect.php';

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin sản phẩm từ form
    $tenSP = $_POST['TenSP'];
    $gia = $_POST['Gia'];
    $soLuong = $_POST['SoLuong'];
    $moTa = $_POST['MoTa'];

    // Thực hiện truy vấn để thêm sản phẩm vào cơ sở dữ liệu
    $query = "INSERT INTO sanpham (TenSP, Gia, SoLuong, MoTa) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$tenSP, $gia, $soLuong, $moTa]);

    // Nếu thêm sản phẩm thành công, chuyển hướng về admin_dashboard.php và thêm tham số 'added=true'
    echo "<script>
            alert('Thêm sản phẩm thành công!');
            window.location.href = 'admin_dashboard.php?added=true';
          </script>";
    exit();
}
?>
