<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Kiểm tra nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenNCC = $_POST['TenNCC'];
    $diaChi = $_POST['DiaChi'];
    $sdt = $_POST['SDT'];
    $email = $_POST['Email'];
    $website = $_POST['Website'];

    // Chuẩn bị câu lệnh SQL để thêm nhà cung cấp
    $query = "INSERT INTO Nhacungcap (TenNCC, DiaChi, SDT, Email, Website) VALUES (:TenNCC, :DiaChi, :SDT, :Email, :Website)";
    $stmt = $conn->prepare($query);

    // Gán giá trị cho các tham số
    $stmt->bindParam(':TenNCC', $tenNCC);
    $stmt->bindParam(':DiaChi', $diaChi);
    $stmt->bindParam(':SDT', $sdt);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':Website', $website);

    // Thực thi câu lệnh SQL và kiểm tra kết quả
    if ($stmt->execute()) {
        // Sau khi thêm thành công, chuyển hướng về trang quản lý nhà cung cấp với thông báo
        header("Location: quanly_nhacungcap.php?added=true");
        exit;
    } else {
        // Nếu có lỗi xảy ra, hiển thị thông báo lỗi
        echo '<script>alert("Lỗi khi thêm nhà cung cấp!");</script>';
    }
}
?>
