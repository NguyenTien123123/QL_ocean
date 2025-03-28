<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy danh sách sản phẩm
$query = "SELECT * FROM sanpham";
$stmt = $conn->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Lấy dữ liệu và gán vào biến $products
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
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
    <!-- Phần danh sách sản phẩm -->
    <div class="left-panel">
        <h2>Quản lý Sản phẩm</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
            <?php if ($products): ?>
                <?php foreach ($products as $row) { ?>
                <tr>
                    <td><?= $row['SPID'] ?></td>
                    <td><?= $row['TenSP'] ?></td>
                    <td><?= number_format($row['Gia'], 0, ',', '.') ?> đ</td>
                    <td><?= $row['SoLuong'] ?></td>
                    <td><?= $row['MoTa'] ?></td>
                    <td><a href="edit_product.php?id=<?= $row['SPID'] ?>">Sửa</a></td>
                    
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Không có sản phẩm nào!</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Phần thêm sản phẩm -->
    <div class="right-panel">
        <h2>Thêm Sản Phẩm Mới</h2>
        <form method="POST" action="add_product.php">
            <label for="TenSP">Tên sản phẩm:</label>
            <input type="text" id="TenSP" name="TenSP" required>

            <label for="Gia">Giá:</label>
            <input type="number" id="Gia" name="Gia" required>

            <label for="SoLuong">Số lượng:</label>
            <input type="number" id="SoLuong" name="SoLuong" required>

            <label for="MoTa">Mô tả:</label>
            <textarea id="MoTa" name="MoTa" required></textarea>

            <button type="submit">Thêm sản phẩm</button>
        </form>
    </div>
    
</div>

</body>
</html>
