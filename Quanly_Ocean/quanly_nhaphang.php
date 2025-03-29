<?php
include 'db_connect.php'; // Kết nối CSDL

// Xóa đơn nhập hàng
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM nhaphang WHERE NHID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
}

// Lấy danh sách đơn nhập hàng
$query = "
    SELECT nh.NHID, nh.NVID, nh.NgayNhap, nh.TongTien, nv.Ten AS NhanVien
    FROM nhaphang nh
    LEFT JOIN nhanvien nv ON nh.NVID = nv.NVID
";
$stmt = $conn->prepare($query);
$stmt->execute();
$imports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhập hàng</title>
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

        .left-panel {
            width: 70%;
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }

        .right-panel {
            width: 25%;
            background: #222;
            padding: 20px;
            border-radius: 10px;
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

        button {
            background-color: #ffcc00;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #ff9900;
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

        a {
            color: #FFD700;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h2>Quản lý nhập hàng</h2>

    <div class="container">
        <!-- Menu Sidebar -->
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

        <!-- Content Section -->
        <div id="content">
            <!-- Left Panel: Danh sách đơn nhập hàng -->
            <div class="left-panel">
                <h3>Danh sách đơn nhập hàng</h3>
                <table>
                    <tr>
                        <th>ID Nhập hàng</th>
                        <th>Nhân viên</th>
                        <th>Ngày nhập</th>
                        <th>Tổng tiền</th>
                        <th>Hành động</th>
                    </tr>
                    <?php foreach ($imports as $import) { ?>
                        <tr>
                            <td><?= $import['NHID'] ?></td>
                            <td><?= $import['NhanVien'] ?></td>
                            <td><?= $import['NgayNhap'] ?></td>
                            <td><?= number_format($import['TongTien'], 2) ?> VNĐ</td>
                            <td>
                                <a href="chitiet_nhaphang.php?nhid=<?= $import['NHID'] ?>">Chi tiết</a> |
                                <button onclick="deleteImport(<?= $import['NHID'] ?>)">Xóa</button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <!-- Right Panel: Thêm đơn nhập hàng -->
            <div class="right-panel">
                <h3>Thêm đơn nhập hàng</h3>
                <form id="addImportForm">
                    <label for="nhanvien">Chọn nhân viên:</label>
                    <select id="nhanvien" required>
                        <option value="">Chọn nhân viên</option>
                        <?php
                        // Lấy danh sách nhân viên
                        $query = "SELECT * FROM nhanvien";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($employees as $employee) {
                            echo "<option value='{$employee['NVID']}'>{$employee['Ten']}</option>";
                        }
                        ?>
                    </select>

                    <label for="ngaynhap">Ngày nhập:</label>
                    <input type="date" id="ngaynhap" required>

                    <label for="tongtien">Tổng tiền_Nhập hàng:</label>
                    <input type="text" id="tongtien" placeholder="Tổng tiền" value="0" required>

                    <button type="submit">Thêm đơn nhập hàng</button>
                </form>

                <script>
                    // Set the default date to today's date
                    document.getElementById("ngaynhap").value = new Date().toISOString().split('T')[0];

                    // Thêm đơn nhập hàng mới
                    document.getElementById("addImportForm").addEventListener("submit", function (event) {
                        event.preventDefault();
                        let nhanvien = document.getElementById("nhanvien").value;
                        let ngaynhap = document.getElementById("ngaynhap").value;
                        let tongtien = document.getElementById("tongtien").value;

                        fetch("nhaphang_ajax.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `action=add&nhanvien=${nhanvien}&ngaynhap=${ngaynhap}&tongtien=${tongtien}`
                        })
                            .then(response => response.text())
                            .then(data => {
                                alert(data); // Thông báo sau khi thêm đơn
                                location.reload(); // Reload trang
                            });
                    });
                    // Xóa đơn nhập hàng
                    function deleteImport(nhid) {
                        if (confirm("Bạn có chắc chắn muốn xóa đơn nhập hàng này?")) {
                            window.location.href = `quanly_nhaphang.php?delete_id=${nhid}`;
                        }
                    }
                </script>
</body>

</html>