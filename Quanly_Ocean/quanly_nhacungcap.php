<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy danh sách nhà cung cấp
$query = "SELECT * FROM Nhacungcap";
$stmt = $conn->prepare($query);
$stmt->execute();
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhà Cung Cấp</title>
    <style>
        /* Chia layout làm 2 phần */
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }
        
        .left-panel {
            width: 75%;
        }
        
        .right-panel {
            width: 20%;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
            color: white;
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            background: #333;
            color: white;
        }

        th, td {
            border: 1px solid white;
            padding: 10px;
        }

        th {
            background: #FFD700;
            color: black;
        }

        /* Form */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
            color: #FFD700;
        }

        input, textarea {
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

<div class="container">
    <!-- Phần danh sách nhà cung cấp -->
    <div class="left-panel">
        <h2>Quản lý Nhà Cung Cấp</h2>
        
        <!-- Hiển thị thông báo nếu thêm nhà cung cấp thành công -->
        <?php
        if (isset($_GET['added']) && $_GET['added'] == 'true') {
            echo "<script>alert('Thêm nhà cung cấp thành công!');</script>";
        }
        ?>
        
        <table>
            <tr>
                <th>ID</th>
                <th>Tên nhà cung cấp</th>
                <th>Địa chỉ</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Website</th>
                <th>Hành động</th>
            </tr>
            <?php if ($suppliers): ?>
                <?php foreach ($suppliers as $row) { ?>
                <tr>
                    <td><?= $row['NCCID'] ?></td>
                    <td><?= $row['TenNCC'] ?></td>
                    <td><?= $row['DiaChi'] ?></td>
                    <td><?= $row['SDT'] ?></td>
                    <td><?= $row['Email'] ?></td>
                    <td><?= $row['Website'] ?></td>
                    <td><a href="edit_supplier.php?id=<?= $row['NCCID'] ?>">Sửa</a></td>
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Không có nhà cung cấp nào!</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Phần thêm nhà cung cấp -->
    <div class="right-panel">
        <h2>Thêm Nhà Cung Cấp Mới</h2>
        <form method="POST" action="add_supplier.php">
            <label for="TenNCC">Tên nhà cung cấp:</label>
            <input type="text" id="TenNCC" name="TenNCC" required>

            <label for="DiaChi">Địa chỉ:</label>
            <input type="text" id="DiaChi" name="DiaChi" required>

            <label for="SDT">Số điện thoại:</label>
            <input type="text" id="SDT" name="SDT" required>

            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required>

            <label for="Website">Website:</label>
            <input type="text" id="Website" name="Website" required>

            <button type="submit">Thêm nhà cung cấp</button>
        </form>
    </div>
</div>

</body>
</html>
