<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Lấy danh sách nhân viên
$query = "SELECT * FROM nhanvien";
$stmt = $conn->prepare($query);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Lấy dữ liệu và gán vào biến $employees
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhân viên</title>
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

        input {
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
    <!-- Phần danh sách nhân viên -->
    <div class="left-panel">
        <h2>Quản lý Nhân viên</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Tên nhân viên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Chức vụ</th>
                <th>Hành động</th>
            </tr>
            <?php if ($employees): ?>
                <?php foreach ($employees as $row) { ?>
                <tr>
                    <td><?= $row['NVID'] ?></td>
                    <td><?= $row['Ten'] ?></td>
                    <td><?= $row['Email'] ?></td>
                    <td><?= $row['SDT'] ?></td>
                    <td><?= $row['ChucVu'] ?></td>
                    <td><a href="edit_employee.php?id=<?= $row['NVID'] ?>">Sửa</a></td>
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Không có nhân viên nào!</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Phần thêm nhân viên -->
    <div class="right-panel">
        <h2>Thêm Nhân Viên Mới</h2>
        <form method="POST" action="add_employee.php">
            <label for="Ten">Tên nhân viên:</label>
            <input type="text" id="Ten" name="Ten" required>

            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required>

            <label for="SDT">Số điện thoại:</label>
            <input type="text" id="SDT" name="SDT" required>

            <label for="ChucVu">Chức vụ:</label>
            <input type="text" id="ChucVu" name="ChucVu" required>

            <button type="submit">Thêm nhân viên</button>
        </form>
    </div>

</div>

</body>
</html>
