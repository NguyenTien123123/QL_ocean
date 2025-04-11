<?php
include 'db_connect.php'; // Kết nối CSDL

// Kiểm tra nếu có DHID được truyền vào
if (!isset($_GET['dhid'])) {
    echo "Không tìm thấy đơn hàng!";
    exit;
}

$dhid = $_GET['dhid']; // Lấy DHID từ URL

// Truy vấn thông tin đơn bán hàng hiện tại
$queryExisting = "SELECT KHID, NVID, NgayBan, TongTien FROM donhang WHERE DHID = ?";
$stmtExisting = $conn->prepare($queryExisting);
$stmtExisting->execute([$dhid]);
$existingOrder = $stmtExisting->fetch(PDO::FETCH_ASSOC);

if (!$existingOrder) {
    echo "Không tìm thấy đơn hàng!";
    exit;
}

$existingKHID = $existingOrder['KHID'];
$existingNVID = $existingOrder['NVID'];
$existingNgayBan = $existingOrder['NgayBan'];
$existingTongTien = $existingOrder['TongTien'];

// Lấy danh sách nhân viên và khách hàng
$queryNV = "SELECT * FROM nhanvien";
$stmtNV = $conn->prepare($queryNV);
$stmtNV->execute();
$nhanvienList = $stmtNV->fetchAll(PDO::FETCH_ASSOC);

$queryKH = "SELECT * FROM khachhang";
$stmtKH = $conn->prepare($queryKH);
$stmtKH->execute();
$khachhangList = $stmtKH->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách sản phẩm
$querySP = "SELECT * FROM sanpham";
$stmtSP = $conn->prepare($querySP);
$stmtSP->execute();
$sanphamList = $stmtSP->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách chi tiết sản phẩm trong đơn hàng
$queryOrderDetails = "SELECT ct.SPID, s.TenSP, ct.SoLuong, ct.Gia FROM chitietdonhang ct
                      JOIN sanpham s ON ct.SPID = s.SPID WHERE ct.DHID = ?";
$stmtOrderDetails = $conn->prepare($queryOrderDetails);
$stmtOrderDetails->execute([$dhid]);
$orderDetails = $stmtOrderDetails->fetchAll(PDO::FETCH_ASSOC);

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $khid = $_POST['KHID'];
    $nvid = $_POST['NVID'];
    $ngayban = $_POST['NgayBan'];
    $tongtien = $_POST['TongTien'];

    // Cập nhật thông tin đơn hàng
    $queryUpdate = "UPDATE donhang SET KHID = ?, NVID = ?, NgayBan = ?, TongTien = ? WHERE DHID = ?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->execute([$khid, $nvid, $ngayban, $tongtien, $dhid]);

    // Xóa chi tiết đơn hàng cũ
    $queryDeleteDetails = "DELETE FROM chitietdonhang WHERE DHID = ?";
    $stmtDeleteDetails = $conn->prepare($queryDeleteDetails);
    $stmtDeleteDetails->execute([$dhid]);

    // Thêm lại chi tiết đơn hàng mới
    foreach ($_POST['SPID'] as $key => $spid) {
        $soluong = $_POST['SoLuong'][$key];
        $gia = $_POST['Gia'][$key];

        // Cập nhật chi tiết đơn hàng
        $queryInsertDetail = "INSERT INTO chitietdonhang (DHID, SPID, SoLuong, Gia) VALUES (?, ?, ?, ?)";
        $stmtInsertDetail = $conn->prepare($queryInsertDetail);
        $stmtInsertDetail->execute([$dhid, $spid, $soluong, $gia]);
    }
    if (isset($_POST['sanpham_id'])) {
        $sanpham_id = $_POST['sanpham_id'];

        $query = "SELECT gia_vip_1A, gia_vip_1, gia_vip_2, gia_sl1_5, gia_sl6_16, gia_sl16_50, gia_sl51_100, gia_sl101_200, gia_sl201_300, gia_sl301_400, gia_sl400_1000 FROM sanpham WHERE SPID = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$sanpham_id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode($row);
        }
        exit;
    }

    // Redirect hoặc thông báo thành công
    header("Location: quanly_banhang.php");
    exit;


}

?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selectProduct = document.getElementById("selectProduct");
        const selectedTable = document.getElementById("selectedProducts");
        const giaOption = document.getElementById("giaOption");

        // Cập nhật giá theo mức giá khi thay đổi option
        giaOption.addEventListener("change", function () {
            const selectedGia = this.value;

            // Cập nhật data-gia cho từng option theo mức giá được chọn
            const options = selectProduct.options;
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                if (option.dataset[selectedGia]) {
                    option.setAttribute("data-gia", option.dataset[selectedGia]);
                }
            }
        });

        // Thêm sản phẩm vào bảng chi tiết
        selectProduct.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const productId = selectedOption.value;
            const productName = selectedOption.getAttribute("data-name");
            const productPrice = selectedOption.getAttribute("data-gia");

            if (!productId) return;

            if (document.getElementById("row-" + productId)) {
                alert("Sản phẩm này đã có trong danh sách!");
                return;
            }

            let row = document.createElement("tr");
            row.setAttribute("id", "row-" + productId);
            row.innerHTML = `
            <td>${productName}</td>
            <td><input type="number" name="SoLuong[]" value="1" min="1" required></td>
            <td><input type="number" name="Gia[]" value="${productPrice}" required></td>
            <td><button type="button" onclick="removeProduct('${productId}')">Xóa</button></td>
            <input type="hidden" name="SPID[]" value="${productId}">
        `;
            selectedTable.appendChild(row);
            updateTotalAmount();
        });

        window.removeProduct = function (productId) {
            document.getElementById("row-" + productId)?.remove();
            updateTotalAmount();
        }

        function updateTotalAmount() {
            let totalAmount = 0;
            const rows = document.querySelectorAll("#selectedProducts tr");
            rows.forEach(row => {
                const quantity = row.querySelector("input[name='SoLuong[]']").value;
                const price = row.querySelector("input[name='Gia[]']").value;
                if (quantity && price) {
                    totalAmount += quantity * price;
                }
            });
            document.getElementById("TongTien").value = totalAmount;
        }
    });

</script>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng bán</title>
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

    <h2>Chi tiết đơn hàng bán</h2>

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
            <div class="form-panel">
                <h3>Thông tin đơn hàng</h3>
                <form method="POST">
                    <input type="hidden" name="DHID" value="<?= $_GET['dhid'] ?>" />
                    <label for="KHID">Khách hàng:</label>
                    <select id="KHID" name="KHID" required>
                        <?php foreach ($khachhangList as $kh) { ?>
                            <option value="<?= $kh['KHID'] ?>" <?= ($kh['KHID'] == $existingKHID) ? 'selected' : '' ?>>
                                <?= $kh['Ten'] ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label for="NVID">Nhân viên bán hàng:</label>
                    <select id="NVID" name="NVID" required>
                        <?php foreach ($nhanvienList as $nv) { ?>
                            <option value="<?= $nv['NVID'] ?>" <?= ($nv['NVID'] == $existingNVID) ? 'selected' : '' ?>>
                                <?= $nv['Ten'] ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label for="NgayBan">Ngày bán:</label>
                    <input type="date" id="NgayBan" name="NgayBan" value="<?= $existingNgayBan ?>" required>

                    <label for="TongTien">Tổng tiền:</label>
                    <input type="number" id="TongTien" name="TongTien" value="<?= $existingTongTien ?>" required>
                    <!-- Mức giá -->
                    <div style="margin-bottom: 10px;">
                        <label for="giaOption"><strong>Chọn mức giá:</strong></label>
                        <select id="giaOption" name="giaOption" style="padding: 5px; min-width: 200px;">
                            <option value="gia_vip_1A">VIP 1A</option>
                            <option value="gia_vip_1">VIP 1</option>
                            <option value="gia_vip_2">VIP 2</option>
                            <option value="gia_sl1_5">SL 1-5</option>
                            <option value="gia_sl6_16">SL 6-16</option>
                            <option value="gia_sl16_50">SL 16-50</option>
                            <option value="gia_sl51_100">SL 51-100</option>
                            <option value="gia_sl101_200">SL 101-200</option>
                            <option value="gia_sl201_300">SL 201-300</option>
                            <option value="gia_sl301_400">SL 301-400</option>
                            <option value="gia_sl400_1000">SL 400-1000</option>
                        </select>
                    </div>

                    <!-- Sản phẩm -->
                    <div style="margin-bottom: 10px;">
                        <label for="selectProduct"><strong>Chọn sản phẩm:</strong></label>
                        <select id="selectProduct" name="selectProduct" style="padding: 5px; min-width: 300px;">
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php foreach ($sanphamList as $sp): ?>
                                <option value="<?= $sp['SPID'] ?>" data-name="<?= htmlspecialchars($sp['TenSP']) ?>"
                                    data-gia_vip_1A="<?= $sp['gia_vip_1A'] ?>" data-gia_vip_1="<?= $sp['gia_vip_1'] ?>"
                                    data-gia_vip_2="<?= $sp['gia_vip_2'] ?>" data-gia_sl1="<?= $sp['gia_sl1_5'] ?>"
                                    data-gia_sl6_16="<?= $sp['gia_sl6_16'] ?>" data-gia_sl16_50="<?= $sp['gia_sl16_50'] ?>"
                                    data-gia_sl51_100="<?= $sp['gia_sl51_100'] ?>"
                                    data-gia_sl101_200="<?= $sp['gia_sl101_200'] ?>"
                                    data-gia_sl201_300="<?= $sp['gia_sl201_300'] ?>"
                                    data-gia_sl301_400="<?= $sp['gia_sl301_400'] ?>"
                                    data-gia_sl400_1000="<?= $sp['gia_sl400_1000'] ?>" data-gia="<?= $sp['gia_vip_1A'] ?>"
                                    <!-- mặc định là VIP 1 -->
                                    >
                                    <?= htmlspecialchars($sp['TenSP']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <h3>Sản phẩm đã chọn:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="selectedProducts">
                            <?php foreach ($orderDetails as $detail) { ?>
                                <tr id="row-<?= $detail['SPID'] ?>">
                                    <td><?= htmlspecialchars($detail['TenSP']) ?></td>
                                    <td>
                                        <input type="number" name="SoLuong[]" value="<?= $detail['SoLuong'] ?>" min="1"
                                            required>
                                    </td>
                                    <td>
                                        <input type="number" name="Gia[]" value="<?= $detail['Gia'] ?>" required readonly>
                                    </td>
                                    <td>
                                        <button type="button" onclick="removeProduct('<?= $detail['SPID'] ?>')">Xóa</button>
                                    </td>
                                    <input type="hidden" name="SPID[]" value="<?= $detail['SPID'] ?>">
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <button type="submit">Cập nhật đơn hàng</button>
                    <!-- <table>
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng tiền</th>
                            </tr>
                        </thead>
                        
                    </table> -->
                </form>
            </div>
        </div>
    </div>
</body>

</html>