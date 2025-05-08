<?php
require 'db_connect.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=sanpham.csv');

// Mở output stream và ghi BOM cho UTF-8 (đảm bảo tiếng Việt không lỗi)
$output = fopen('php://output', 'w');
fwrite($output, "\xEF\xBB\xBF"); // BOM UTF-8

// Ghi dòng tiêu đề
fputcsv($output, [
    'ID', 'Tên sản phẩm', 'Giá VIP 1A', 'Giá VIP 1', 'Giá VIP 2',
    'SL 1-5', 'SL 6-16', 'SL 16-50',
    'SL 51-100', 'SL 101-200', 'SL 201-300', 'SL 301-400', 'SL 400-1000',
    'Số lượng', 'Mô tả'
]);

// Lấy dữ liệu từ bảng sanpham
$query = $conn->query("
    SELECT SPID, TenSP, gia_vip_1A, gia_vip_1, gia_vip_2,
           gia_sl1_5, gia_sl6_16, gia_sl16_50, gia_sl51_100,
           gia_sl101_200, gia_sl201_300, gia_sl301_400, gia_sl400_1000,
           SoLuong, MoTa
    FROM sanpham
");

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['SPID'],
        $row['TenSP'],
        $row['gia_vip_1A'],
        $row['gia_vip_1'],
        $row['gia_vip_2'],
        $row['gia_sl1_5'],
        $row['gia_sl6_16'],
        $row['gia_sl16_50'],
        $row['gia_sl51_100'],
        $row['gia_sl101_200'],
        $row['gia_sl201_300'],
        $row['gia_sl301_400'],
        $row['gia_sl400_1000'],
        $row['SoLuong'],
        $row['MoTa']
    ]);
}

fclose($output);
exit;
?>
