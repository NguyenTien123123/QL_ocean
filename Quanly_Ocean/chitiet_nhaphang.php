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
    $nhid = $_POST['NHID']; // NHID của đơn hàng cần cập nhật
    $nvid = $_POST['NVID'];
    $ngaynhap = $_POST['NgayNhap'];
    $tongtien = $_POST['TongTien'];

    // Cập nhật thông tin đơn hàng
    $queryUpdate = "
        UPDATE nhaphang 
        SET NVID = ?, NgayNhap = ?, TongTien = ? 
        WHERE NHID = ?
    ";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->execute([$nvid, $ngaynhap, $tongtien, $nhid]);

    // Cập nhật chi tiết nhập hàng
    // Xóa tất cả sản phẩm cũ trong chi tiết nhập hàng
    $queryDeleteDetails = "DELETE FROM chitietnhaphang WHERE NHID = ?";
    $stmtDeleteDetails = $conn->prepare($queryDeleteDetails);
    $stmtDeleteDetails->execute([$nhid]);

    // Thêm lại các sản phẩm mới cho đơn hàng
    foreach ($_POST['SPID'] as $key => $spid) {
        $soluong = $_POST['SoLuong'][$key];
        $gia = $_POST['Gia'][$key];

        // Thêm vào bảng chi tiết nhập hàng
        $queryInsertDetail = "
            INSERT INTO chitietnhaphang (NHID, SPID, SoLuong, Gia)
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
            flex-direction: column;
        }

        /* Điều chỉnh phần "Thông tin đơn hàng" chiếm 100% chiều rộng */
        .form-panel {
            width: 98%;
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }

        .product-panel {
            width: 98%;
            background-color: #444;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            color: white;
            margin-bottom: 20px;
        }

        .product-panel h4 {
            text-align: center;
            color: #FFD700;
        }

        .product-selection {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: space-between;
        }

        .product-selection div {
            width: 16%;
            /* 6 sản phẩm trên 1 hàng */
            text-align: center;
        }

        .product-selection label {
            display: block;
            margin-bottom: 5px;
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
            <!-- Phần form thêm đơn hàng -->
            <div class="form-panel">
                <h3>Thông tin đơn hàng</h3>
                <form method="POST">
                    <input type="hidden" name="NHID" value="<?= $_GET['nhid'] ?>" /> <!-- Truyền NHID từ URL -->
                    <label for="NVID">Chọn nhân viên:</label>
                    <label for="NVID">Chọn nhà cung cấp:</label>
                    <select id="NVID" name="NVID" required>
                        <?php foreach ($nhacungcapList as $ncc) { ?>
                            <option value="<?= $ncc['NCCID'] ?>" <?= ($ncc['NCCID'] == $existingNVID) ? 'selected' : '' ?>>
                                <?= $ncc['TenNCC'] ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label for="NgayNhap">Ngày nhập:</label>
                    <input type="date" id="NgayNhap" name="NgayNhap" value="<?= $existingNgayNhap ?>" required>

                    <label for="TongTien">Tổng tiền:</label>
                    <input type="number" id="TongTien" name="TongTien" value="<?= $existingTongTien ?>" required>

                    <h4>Chọn sản phẩm</h4>
                    <div class="product-selection">
                        <?php foreach ($sanphamList as $sp) { ?>
                            <div>
                                <label for="SPID"><?= $sp['TenSP'] ?></label>
                                <input type="hidden" name="SPID[]" value="<?= $sp['SPID'] ?>" />
                                <input type="number" name="SoLuong[]" placeholder="Số lượng" required />
                                <input type="number" name="Gia[]" value="<?= $sp['Gia'] ?>" readonly required />
                            </div>
                        <?php } ?>
                    </div>

                    <button type="submit">Cập nhật đơn hàng</button>
                </form>
            </div>

            <!-- Phần danh sách sản phẩm trong đơn hàng -->
            <div class="product-panel">
                <h3>Danh sách sản phẩm trong đơn hàng</h3>
                <table>
                    <?php
                    include 'db_connect.php'; // Kết nối CSDL
                    
                    // Kiểm tra nếu có NHID được truyền vào
                    if (!isset($_GET['nhid'])) {
                        echo "Không tìm thấy đơn hàng!";
                        exit;
                    }

                    $nhid = $_GET['nhid']; // Lấy NHID từ URL
                    
                    // Truy vấn danh sách sản phẩm trong đơn nhập hàng
                    $queryDetails = "
    SELECT cth.SPID, sp.TenSP, cth.SoLuong, cth.Gia
    FROM chitietnhaphang cth
    JOIN sanpham sp ON cth.SPID = sp.SPID
    WHERE cth.NHID = ?
";
                    $stmtDetails = $conn->prepare($queryDetails);
                    $stmtDetails->execute([$nhid]);
                    $orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <h3>Danh sách sản phẩm trong đơn hàng #<?= $nhid ?></h3>
                    <table>
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                        </tr>
                        <?php foreach ($orderDetails as $item) { ?>
                            <tr>
                                <td><?= $item['SPID'] ?></td>
                                <td><?= $item['TenSP'] ?></td>
                                <td><?= $item['SoLuong'] ?></td>
                                <td><?= number_format($item['Gia'], 0) ?> VNĐ</td>
                            </tr>
                        <?php } ?>
                    </table>
            </div>
        </div>
    </div>
</body>

</html>