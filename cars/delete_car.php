<?php
include('database.php');

$response = ['success' => false, 'message' => ''];

try {
    $id = $_POST['id'] ?? null;
    
    if (!$id) {
        throw new Exception("Invalid car ID");
    }

    $stmt = $pdo->prepare('SELECT image_path FROM cars WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $car = $stmt->fetch();
    
    if (!$car) {
        throw new Exception("Car not found");
    }

    $stmt = $pdo->prepare('DELETE FROM cars WHERE id = :id');
    $stmt->execute(['id' => $id]);

    if ($car['image_path'] && file_exists($car['image_path'])) {
        unlink($car['image_path']);
    }
    
    $response['success'] = true;
    $response['message'] = 'Car deleted successfully';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>