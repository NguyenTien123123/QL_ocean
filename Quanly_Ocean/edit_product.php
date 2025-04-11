<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy ID sản phẩm từ URL
$SPID = isset($_GET['id']) ? $_GET['id'] : 0;

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM sanpham WHERE SPID = :SPID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':SPID', $SPID);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu sản phẩm không tồn tại
if (!$product) {
    echo "Sản phẩm không tồn tại!";
    exit;
}

// Kiểm tra nếu người dùng đã gửi form
// Xử lý khi người dùng gửi form sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TenSP = $_POST['TenSP'];
    $gia_vip_1A = $_POST['gia_vip_1A'];
    $gia_vip_1 = $_POST['gia_vip_1'];
    $gia_vip_2 = $_POST['gia_vip_2'];
    $gia_sl1_5 = $_POST['gia_sl1_5'];
    $gia_sl6_16 = $_POST['gia_sl6_16'];
    $gia_sl16_50 = $_POST['gia_sl16_50'];
    $gia_sl51_100 = $_POST['gia_sl51_100'];
    $gia_sl101_200 = $_POST['gia_sl101_200'];
    $gia_sl201_300 = $_POST['gia_sl201_300'];
    $gia_sl301_400 = $_POST['gia_sl301_400'];
    $gia_sl400_1000 = $_POST['gia_sl400_1000'];
    $SoLuong = $_POST['SoLuong'];
    $MoTa = $_POST['MoTa'];

    $query = "UPDATE sanpham SET 
                TenSP = :TenSP, 
                gia_vip_1A = :gia_vip_1A,
                gia_vip_1 = :gia_vip_1, 
                gia_vip_2 = :gia_vip_2, 
                gia_sl1_5 = :gia_sl1_5, 
                gia_sl6_16 = :gia_sl6_16, 
                gia_sl16_50 = :gia_sl16_50, 
                gia_sl51_100 = :gia_sl51_100, 
                gia_sl101_200 = :gia_sl101_200, 
                gia_sl201_300 = :gia_sl201_300, 
                gia_sl301_400 = :gia_sl301_400, 
                gia_sl400_1000 = :gia_sl400_1000, 
                SoLuong = :SoLuong, 
                MoTa = :MoTa 
              WHERE SPID = :SPID";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':TenSP', $TenSP);
    $stmt->bindParam(':gia_vip_1A', $gia_vip_1A);
    $stmt->bindParam(':gia_vip_1', $gia_vip_1);
    $stmt->bindParam(':gia_vip_2', $gia_vip_2);
    $stmt->bindParam(':gia_sl1_5', $gia_sl1_5);
    $stmt->bindParam(':gia_sl6_16', $gia_sl6_16);
    $stmt->bindParam(':gia_sl16_50', $gia_sl16_50);
    $stmt->bindParam(':gia_sl51_100', $gia_sl51_100);
    $stmt->bindParam(':gia_sl101_200', $gia_sl101_200);
    $stmt->bindParam(':gia_sl201_300', $gia_sl201_300);
    $stmt->bindParam(':gia_sl301_400', $gia_sl301_400);
    $stmt->bindParam(':gia_sl400_1000', $gia_sl400_1000);
    $stmt->bindParam(':SoLuong', $SoLuong);
    $stmt->bindParam(':MoTa', $MoTa);
    $stmt->bindParam(':SPID', $SPID);

    if ($stmt->execute()) {
        // Redirect to 'quanly_sanpham.php' after a successful update
        echo '<script>
            alert("Cập nhật sản phẩm thành công!");
            window.location.href = "quanly_sanpham.php";
        </script>';
        exit();
    } else {
        echo '<script>alert("Lỗi khi cập nhật sản phẩm!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #ffcc00;
            text-align: center;
            margin-top: 20px;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .menu {
            width: 250px;
            background-color: #222;
            padding-top: 20px;
            text-align: left;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            overflow-y: auto;
            border-right: 2px solid #ffcc00;
        }

        .menu ul {
            list-style: none;
            padding: 0;
        }

        .menu li {
            margin: 10px 0;
        }

        .menu a {
            text-decoration: none;
            color: #ffcc00;
            font-size: 18px;
            padding: 10px;
            display: block;
            text-align: center;
            border: 2px solid #ffcc00;
            border-radius: 5px;
            transition: 0.3s;
        }

        .menu a:hover {
            background-color: #ffcc00;
            color: #1e1e1e;
        }

        #content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            background-color: #333;
            border-radius: 10px;
            border: 2px solid #ffcc00;
            height: calc(100vh - 40px);
            color: #fff;
            overflow-y: auto;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            color: #FFD700;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            background: #333;
            color: white;
            border: 1px solid #FFD700;
            border-radius: 5px;
        }

        button {
            margin-top: 15px;
            background: #FFD700;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h2>Quản lý Sản Phẩm</h2>

    <div class="container">
        <div class="menu">
            <ul>
                <li><a href="quanly_nhaphang.php">Quản lý nhập hàng</a></li>
                <li><a href="quanly_banhang.php">Quản lý bán hàng</a></li>
                <li><a href="quanly_sanpham.php">Quản lý sản phẩm</a></li>
                <li><a href="quanly_khachhang.php">Quản lý khách hàng</a></li>
                <li><a href="quanly_nhanvien.php">Quản lý nhân viên</a></li>
                <li><a href="quanly_nhacungcap.php">Quản lý nhà cung cấp</a></li>
                <li><a href="thongke_doanhthu_nvnv.php">Báo cáo doanh thu theo nhân viên</a></li>
                <li><a href="thongke_doanhthu_sp.php">Báo cáo doanh thu theo sản phẩm</a></li>
            </ul>
        </div>

        <div id="content">
            <h2>Sửa Sản Phẩm</h2>
            <form method="POST">
                <label for="TenSP">Tên sản phẩm:</label>
                <input type="text" id="TenSP" name="TenSP" value="<?= $product['TenSP'] ?>" required>

                <label for="gia_vip_1">Giá VIP 1A:</label>
                <input type="number" id="gia_vip_1A" name="gia_vip_1A" value="<?= $product['gia_vip_1A'] ?>" required>

                <label for="gia_vip_1">Giá VIP 1:</label>
                <input type="number" id="gia_vip_1" name="gia_vip_1" value="<?= $product['gia_vip_1'] ?>" required>

                <label for="gia_vip_2">Giá VIP 2:</label>
                <input type="number" id="gia_vip_2" name="gia_vip_2" value="<?= $product['gia_vip_2'] ?>" required>

                <label for="gia_sl1_5">Giá SL 1-5:</label>
                <input type="number" id="gia_sl1_5" name="gia_sl1_5" value="<?= $product['gia_sl1_5'] ?>" required>

                <label for="gia_sl6_16">Giá SL 6-16:</label>
                <input type="number" id="gia_sl6_16" name="gia_sl6_16" value="<?= $product['gia_sl6_16'] ?>" required>

                <label for="gia_sl16_50">Giá SL 16-50:</label>
                <input type="number" id="gia_sl16_50" name="gia_sl16_50" value="<?= $product['gia_sl16_50'] ?>"
                    required>

                <label for="gia_sl51_100">Giá SL 51-100:</label>
                <input type="number" id="gia_sl51_100" name="gia_sl51_100" value="<?= $product['gia_sl51_100'] ?>"
                    required>

                <label for="gia_sl101_200">Giá SL 101-200:</label>
                <input type="number" id="gia_sl101_200" name="gia_sl101_200" value="<?= $product['gia_sl101_200'] ?>"
                    required>

                <label for="gia_sl201_300">Giá SL 201-300:</label>
                <input type="number" id="gia_sl201_300" name="gia_sl201_300" value="<?= $product['gia_sl201_300'] ?>"
                    required>

                <label for="gia_sl301_400">Giá SL 301-400:</label>
                <input type="number" id="gia_sl301_400" name="gia_sl301_400" value="<?= $product['gia_sl301_400'] ?>"
                    required>

                <label for="gia_sl400_1000">Giá SL 400-1000:</label>
                <input type="number" id="gia_sl400_1000" name="gia_sl400_1000" value="<?= $product['gia_sl400_1000'] ?>"
                    required>

                <label for="SoLuong">Số lượng:</label>
                <input type="number" id="SoLuong" name="SoLuong" value="<?= $product['SoLuong'] ?>" required>

                <label for="MoTa">Mô tả:</label>
                <textarea id="MoTa" name="MoTa" required><?= $product['MoTa'] ?></textarea>

                <button type="submit">Cập nhật</button>
            </form>
        </div>
    </div>

</body>

</html>