<?php
include('database.php');

$response = ['success' => false, 'message' => ''];

try {
    $make = htmlspecialchars($_POST['make'] ?? '');
    $model = htmlspecialchars($_POST['model'] ?? '');
    $year = htmlspecialchars($_POST['year'] ?? '');
    $price = htmlspecialchars($_POST['price'] ?? '');

    if (empty($make) || empty($model) || empty($year) || empty($price)) {
        throw new Exception("All fields are required");
    }

    $image_path = null;
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
                $image_path = $targetPath;
            } else {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        } else {
            throw new Exception("File is not an image.");
        }
    }

    $sql = 'INSERT INTO cars(make, model, year, price, image_path) 
            VALUES(:make, :model, :year, :price, :image_path)';
    
    $stmt = $pdo->prepare($sql);
    $params = [
        'make' => $make,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'image_path' => $image_path
    ];
    
    $stmt->execute($params);
    
    $response['success'] = true;
    $response['message'] = 'Car added successfully';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>