<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "./uploads/";
        $imageName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $imageName;

        // Chỉ cho phép các định dạng file nhất định
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // File đã được upload thành công
            } else {
                echo "Xin lỗi, có lỗi xảy ra khi upload file của bạn.";
                exit();
            }
        } else {
            echo "Xin lỗi, chỉ chấp nhận các file có định dạng JPG, JPEG, PNG & GIF.";
            exit();
        }
    }

    $sql = "UPDATE products SET name='$name', description='$description'";

    if ($imageName != '') {
        $sql .= ", image='$targetFilePath'";
    }

    $sql .= " WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: product.php");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa sản phẩm</title>
</head>
<body>
    <div class="container">
        <h2>Sửa sản phẩm</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            Tên sản phẩm: <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>
            Mô tả: <textarea name="description" required><?php echo $row['description']; ?></textarea><br>
            <button type="submit">Lưu</button>
        </form>
    </div>
</body>
</html>
