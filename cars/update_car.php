<?php
include('database.php');

$response = ['success' => false, 'message' => ''];

try {
    $id = $_POST['id'] ?? null;
    $make = htmlspecialchars($_POST['make'] ?? '');
    $model = htmlspecialchars($_POST['model'] ?? '');
    $year = htmlspecialchars($_POST['year'] ?? '');
    $price = htmlspecialchars($_POST['price'] ?? '');
    
    if (!$id) {
        throw new Exception("Invalid car ID");
    }

    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $car = $stmt->fetch();
    
    if (!$car) {
        throw new Exception("Car not found");
    }

    $image_path = $car['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;

        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                if ($image_path && file_exists($image_path)) {
                    unlink($image_path);
                }
                $image_path = $targetPath;
            } else {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        } else {
            throw new Exception("File is not an image.");
        }
    }

    $sql = 'UPDATE cars SET make = :make, model = :model, year = :year, 
            price = :price, image_path = :image_path WHERE id = :id';
    
    $stmt = $pdo->prepare($sql);
    $params = [
        'make' => $make,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'image_path' => $image_path,
        'id' => $id
    ];
    
    $stmt->execute($params);
    
    $response['success'] = true;
    $response['message'] = 'Car updated successfully';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>