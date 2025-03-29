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

<h2>Quản lý nhập hàng</h2>

<!-- Form thêm đơn nhập hàng -->
<form id="addImportForm">
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
    <input type="date" id="ngaynhap" required>
    <input type="text" id="tongtien" placeholder="Tổng tiền" required>
    <button type="submit">Thêm đơn nhập hàng</button>
</form>

<!-- Bảng hiển thị đơn nhập hàng -->
<table border="1" style="width:100%; text-align:center;">
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

<script>
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
        }).then(() => location.reload());
    });

    // Xóa đơn nhập hàng
    function deleteImport(id) {
        if (confirm("Bạn có chắc muốn xóa đơn nhập hàng này?")) {
            fetch(`quanly_nhaphang.php?delete_id=${id}`).then(() => location.reload());
        }
    }
</script>