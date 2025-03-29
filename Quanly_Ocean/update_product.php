<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Lấy thông tin sản phẩm từ form
  $spid = $_POST['SPID'];
  $tenSP = $_POST['TenSP'];
  $gia = $_POST['Gia'];
  $soLuong = $_POST['SoLuong'];
  $moTa = $_POST['MoTa'];

  // Thực hiện truy vấn để cập nhật thông tin sản phẩm trong cơ sở dữ liệu
  $query = "UPDATE sanpham SET TenSP = ?, Gia = ?, SoLuong = ?, MoTa = ? WHERE SPID = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$tenSP, $gia, $soLuong, $moTa, $spid]);

  // Nếu cập nhật thành công, hiển thị thông báo và quay lại quanly_sanpham.php với tham số 'updated=true'
  echo "<script>
            alert('Cập nhật sản phẩm thành công!');
            window.location.href = 'quanly_sanpham.php?updated=true';
          </script>";
  exit();
}
?>