<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, 'r')) !== false) {
        // Bỏ qua dòng tiêu đề
        fgetcsv($handle, 1000, ',');

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($data) < 15) {
                continue; // bỏ qua dòng thiếu cột
            }

            $SPID = trim($data[0]);
            $TenSP = trim($data[1]);
            $gia_vip_1A = $data[2] !== '' ? $data[2] : 0;
            $gia_vip_1 = $data[3] !== '' ? $data[3] : 0;
            $gia_vip_2 = $data[4] !== '' ? $data[4] : 0;
            $gia_sl1_5 = $data[5] !== '' ? $data[5] : 0;
            $gia_sl6_16 = $data[6] !== '' ? $data[6] : 0;
            $gia_sl16_50 = $data[7] !== '' ? $data[7] : 0;
            $gia_sl51_100 = $data[8] !== '' ? $data[8] : 0;
            $gia_sl101_200 = $data[9] !== '' ? $data[9] : 0;
            $gia_sl201_300 = $data[10] !== '' ? $data[10] : 0;
            $gia_sl301_400 = $data[11] !== '' ? $data[11] : 0;
            $gia_sl400_1000 = $data[12] !== '' ? $data[12] : 0;
            $SoLuong = $data[13] !== '' ? $data[13] : 0;
            $MoTa = $data[14];

            $tenSP = mb_convert_encoding($TenSP, 'UTF-8', 'auto');

            $stmt = $conn->prepare("SELECT COUNT(*) FROM sanpham WHERE SPID = ? OR TenSP COLLATE utf8mb4_general_ci = ?");
            $stmt->execute([$SPID, $tenSP]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {

                echo "<script>alert('Lỗi: Trùng SPID hoặc Tên sản phẩm ở dòng có SPID: $SPID - $TenSP'); window.location.href='import_sanpham.php';</script>";
                exit();
            }

            $query = "INSERT INTO sanpham (SPID, TenSP, gia_vip_1A, gia_vip_1, gia_vip_2, gia_sl1_5, gia_sl6_16, gia_sl16_50, gia_sl51_100, gia_sl101_200, gia_sl201_300, gia_sl301_400, gia_sl400_1000, SoLuong, MoTa) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                $SPID,
                $TenSP,
                $gia_vip_1A,
                $gia_vip_1,
                $gia_vip_2,
                $gia_sl1_5,
                $gia_sl6_16,
                $gia_sl16_50,
                $gia_sl51_100,
                $gia_sl101_200,
                $gia_sl201_300,
                $gia_sl301_400,
                $gia_sl400_1000,
                $SoLuong,
                $MoTa
            ]);
        }

        fclose($handle);
        echo "<script>alert('Nhập dữ liệu thành công!'); window.location.href='quanly_sanpham.php';</script>";
        exit();
    } else {
        echo "<script>alert('Không thể mở file CSV.');</script>";
    }
}
?>

<!-- Giao diện upload căn giữa -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Import Sản phẩm</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f2f2f2;
        }

        .import-container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="file"] {
            margin: 20px 0;
        }

        button {
            padding: 8px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        h2 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="import-container">
        <h2>Import Sản phẩm từ Excel (.csv)</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="csv_file" accept=".csv" required>
            <br>
            <button type="submit">Import</button>
        </form>
    </div>
</body>

</html>