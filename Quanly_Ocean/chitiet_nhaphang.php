<?php
include 'db_connect.php'; // Kết nối CSDL

// Lấy danh sách nhà cung cấp và sản phẩm để chọn từ form
$queryNCC = "SELECT * FROM nhacungcap"; // Thay đổi tên bảng nếu cần
$stmtNCC = $conn->prepare($queryNCC);
$stmtNCC->execute();
$nhacungcapList = $stmtNCC->fetchAll(PDO::FETCH_ASSOC);

$querySP = "SELECT * FROM sanpham"; // Thay đổi tên bảng nếu cần
$stmtSP = $conn->prepare($querySP);
$stmtSP->execute();
$sanphamList = $stmtSP->fetchAll(PDO::FETCH_ASSOC);

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lưu thông tin đơn nhập hàng
    $nvid = $_POST['NVID'];
    $ngaynhap = $_POST['NgayNhap'];
    $tongtien = $_POST['TongTien'];

    // Thêm vào bảng nhaphang
    $queryInsert = "
        INSERT INTO nhaphang (NVID, NgayNhap, TongTien) 
        VALUES (?, ?, ?)
    ";
    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->execute([$nvid, $ngaynhap, $tongtien]);

    // Lấy NHID vừa thêm vào
    $nhid = $conn->lastInsertId();

    // Lưu thông tin chi tiết đơn hàng
    foreach ($_POST['SPID'] as $key => $spid) {
        $soluong = $_POST['SoLuong'][$key];
        $gia = $_POST['Gia'][$key];

        $queryInsertDetail = "
            INSERT INTO chitietnhaphang (DHID, SPID, SoLuong, Gia)
            VALUES (?, ?, ?, ?)
        ";
        $stmtInsertDetail = $conn->prepare($queryInsertDetail);
        $stmtInsertDetail->execute([$nhid, $spid, $soluong, $gia]);
    }

    // Redirect hoặc thông báo thành công
    header("Location: quanly_nhaphang.php"); // Quay lại trang quản lý nhập hàng
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập đơn hàng</title>
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
            display: flex;
        }

        /* Chia layout 70% và 25% */
        .left-panel {
            width: 70%;
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }

        .right-panel {
            width: 25%;
            background-color: #444;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            color: white;
            margin-left: 20px;
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            background: #333;
            color: white;
        }

        th,
        td {
            border: 1px solid white;
            padding: 10px;
        }

        th {
            background: #FFD700;
            color: black;
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
        select {
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

        button:hover {
            background-color: #ff9900;
        }

        .back-btn {
            background-color: #ffcc00;
            padding: 10px 20px;
            border: none;
            color: black;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            text-align: center;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #ff9900;
        }
    </style>
</head>

<body>

    <h2>Nhập đơn hàng mới</h2>

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
            <!-- Phần danh sách sản phẩm -->
            <div class="left-panel">
                <h3>Danh sách sản phẩm trong đơn hàng</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                    </tr>
                    <!-- Dữ liệu sẽ được hiển thị từ CSDL nếu có sản phẩm -->
                    <!-- Ví dụ: -->
                    <tr>
                        <td>1</td>
                        <td>Sản phẩm 1</td>
                        <td>100,000 đ</td>
                        <td>2</td>
                        <td>200,000 đ</td>
                    </tr>
                </table>
            </div>

            <!-- Phần form thêm đơn hàng -->
            <div class="right-panel">
                <h3>Thông tin đơn hàng</h3>
                <form method="POST">
                    <label for="NVID">Chọn nhà cung cấp:</label>
                    <select id="NVID" name="NVID" required>
                        <?php foreach ($nhacungcapList as $ncc) { ?>
                            <option value="<?= $ncc['NCCID'] ?>"><?= $ncc['TenNCC'] ?></option>
                        <?php } ?>
                    </select>

                    <label for="NgayNhap">Ngày nhập:</label>
                    <input type="date" id="NgayNhap" name="NgayNhap" required>

                    <label for="TongTien">Tổng tiền:</label>
                    <input type="number" id="TongTien" name="TongTien" required>

                    <h3>Chi tiết sản phẩm</h3>
                    <div id="products">
                        <div class="product-item">
                            <label for="SPID[]">Chọn sản phẩm:</label>
                            <select name="SPID[]" required>
                                <?php foreach ($sanphamList as $sp) { ?>
                                    <option value="<?= $sp['SPID'] ?>"><?= $sp['TenSP'] ?></option>
                                <?php } ?>
                            </select>

                            <label for="SoLuong[]">Số lượng:</label>
                            <input type="number" name="SoLuong[]" required>

                            <label for="Gia[]">Giá:</label>
                            <input type="number" name="Gia[]" required>
                        </div>
                    </div>

                    <button type="submit">Thêm đơn hàng</button>
                </form>

                <a href="quanly_nhaphang.php" class="back-btn">Quay lại</a>
            </div>
        </div>
    </div>

</body>

</html>
