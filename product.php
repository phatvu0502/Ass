<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
    exit();
}

require_once('db.php');

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM products";
$result = executeQuery($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script type="text/javascript">
        function confirmDelete(id) {
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
                window.location.href = "delete_product.php?id=" + id;
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <h3>Product List</h3>
    <table>
        <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Mô tả</th>
                <th>Hình ảnh</th>
                <th>Sửa</th>
                <th>Xóa</th>
        </tr>
        <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td><img src='" . $row["image"] . "' alt='" . $row["name"] . "' ></td>";
                    // Liên kết sửa sản phẩm
                    echo "<td><button class='add'><a href='./edit_product.php?id=" . $row["id"] . "'>Sửa</a></button></td>";
                    // Liên kết xóa sản phẩm
                    echo "<td><button class='add' onclick='confirmDelete(" . $row["id"] . ")'>Xóa</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Không có sản phẩm</td></tr>";
            }
            ?>
    </table>
    <br>
    <button class="add"><a href="./add_product.php">Add Product</a></button>
    <button class="add"><a href="logout.php">Logout</a></button>
    </div>
    </body>
</html>
