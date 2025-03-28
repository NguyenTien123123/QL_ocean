<?php
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Kiểm tra nếu có tham số 'id' trong URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin nhân viên từ cơ sở dữ liệu
    $query = "SELECT * FROM nhanvien WHERE NVID = :NVID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':NVID', $id);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        echo "Nhân viên không tồn tại!";
        exit();
    }

    // Xử lý khi người dùng gửi form sửa nhân viên
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ten = $_POST['Ten'];
        $email = $_POST['Email'];
        $sdt = $_POST['SDT'];
        $chucvu = $_POST['ChucVu'];

        $query = "UPDATE nhanvien SET Ten = :Ten, Email = :Email, SDT = :SDT, ChucVu = :ChucVu WHERE NVID = :NVID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':Ten', $ten);
        $stmt->bindParam(':Email', $email);
        $stmt->bindParam(':SDT', $sdt);
        $stmt->bindParam(':ChucVu', $chucvu);
        $stmt->bindParam(':NVID', $id);

        if ($stmt->execute()) {
            echo '<script>alert("Cập nhật nhân viên thành công!"); window.location.href="quanly_nhanvien.php";</script>';
        } else {
            echo '<script>alert("Lỗi khi cập nhật nhân viên!");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Nhân viên</title>
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
        }

        .form-container {
            max-width: 600px;
            margin: auto;
        }

        label {
            font-weight: bold;
            color: #ffcc00;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            background-color: #222;
            color: #fff;
            border: 2px solid #ffcc00;
            border-radius: 5px;
        }

        button {
            background-color: #ffcc00;
            color: #333;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #e5a900;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="menu">
        <ul>
            <li><a href="quanly_nhanvien.php">Quản lý nhân viên</a></li>
            <li><a href="quanly_sanpham.php">Quản lý sản phẩm</a></li>
            <li><a href="quanly_donhang.php">Quản lý đơn hàng</a></li>
        </ul>
    </div>

    <div id="content">
        <h2>Sửa thông tin nhân viên</h2>
        <div class="form-container">
            <form method="POST">
                <label for="Ten">Tên nhân viên:</label>
                <input type="text" id="Ten" name="Ten" value="<?= $employee['Ten'] ?>" required>

                <label for="Email">Email:</label>
                <input type="email" id="Email" name="Email" value="<?= $employee['Email'] ?>" required>

                <label for="SDT">Số điện thoại:</label>
                <input type="text" id="SDT" name="SDT" value="<?= $employee['SDT'] ?>" required>

                <label for="ChucVu">Chức vụ:</label>
                <input type="text" id="ChucVu" name="ChucVu" value="<?= $employee['ChucVu'] ?>" required>

                <button type="submit">Cập nhật nhân viên</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
