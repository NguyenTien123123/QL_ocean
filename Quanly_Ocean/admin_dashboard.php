<!-- <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .loading {
            color: #ffcc00;
            font-size: 24px;
            text-align: center;
            margin-top: 50px;
        }

        .alert {
            color: green;
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            border-radius: 5px;
        }
    </style>
    <script>
        function loadPage(page) {
            const content = document.getElementById("content");
            content.innerHTML = "<div class='loading'>Đang tải...</div>"; // Hiển thị loading

            const xhr = new XMLHttpRequest();
            xhr.open("GET", page, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    content.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // window.onload = function() {
        //     // Kiểm tra URL có tham số 'added=true' hay 'updated=true'
        //     const urlParams = new URLSearchParams(window.location.search);
        //     if (urlParams.has('added') && urlParams.get('added') === 'true') {
        //         // Quay lại trang trước sau khi thêm thành công
        //         setTimeout(function() {
        //             history.back();
        //         }, 1000); // Đợi 2 giây trước khi quay lại trang cũ
        //     } else if (urlParams.has('updated') && urlParams.get('updated') === 'true') {
        //         loadPage('quanly_sanpham.php'); // Tự động load trang quản lý sản phẩm sau khi cập nhật
        //     }
        // }
    </script>
</head>
<body>

    <h2>Hệ thống</h2>

    <div class="container">
        <div class="menu">
            <ul>
                <li><a href="#" onclick="loadPage('quanly_nhaphang.php')">Quản lý nhập hàng</a></li>
                <li><a href="#" onclick="loadPage('quanly_banhang.php')">Quản lý bán hàng</a></li>
                <li><a href="#" onclick="loadPage('quanly_sanpham.php')">Quản lý sản phẩm</a></li>
                <li><a href="#" onclick="loadPage('quanly_khachhang.php')">Quản lý khách hàng</a></li>
                <li><a href="#" onclick="loadPage('quanly_nhanvien.php')">Quản lý nhân viên</a></li>
                <li><a href="#" onclick="loadPage('quanly_nhacungcap.php')">Quản lý nhà cung cấp</a></li>
                <li><a href="#" onclick="loadPage('thongke_doanhthu_nvnv.php')">Báo cáo doanh thu theo nhân viên</a></li>
                <li><a href="#" onclick="loadPage('thongke_doanhthu_sp.php')">Báo cáo doanh thu theo sản phẩm</a></li>
            </ul>
        </div>

        <div id="content">
            <h3>Chọn chức năng quản lý để hiển thị nội dung...</h3>
        </div>
    </div>

</body>
</html> -->