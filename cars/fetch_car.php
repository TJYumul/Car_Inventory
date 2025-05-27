<?php
include('database.php');

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? 'view';

if (!$id) {
    die("Invalid request");
}

$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
$stmt->execute(['id' => $id]);
$car = $stmt->fetch();

if (!$car) {
    die("Car not found");
}

if ($action === 'view') {
    $html = '
    <div class="row">
        <div class="col-md-6">
            ' . ($car['image_path'] ? 
                '<img src="' . htmlspecialchars($car['image_path']) . '" class="img-fluid rounded mb-3">' : 
                '<div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded mb-3" style="height: 200px;">
                    <i class="bi bi-car-front" style="font-size: 3rem;"></i>
                </div>') . '
        </div>
        <div class="col-md-6">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Make:</strong> ' . htmlspecialchars($car['make']) . '</li>
                <li class="list-group-item"><strong>Model:</strong> ' . htmlspecialchars($car['model']) . '</li>
                <li class="list-group-item"><strong>Year:</strong> ' . htmlspecialchars($car['year']) . '</li>
                <li class="list-group-item"><strong>Price:</strong> $' . number_format($car['price'], 2) . '</li>
            </ul>
        </div>
    </div>';
    echo $html;
} elseif ($action === 'edit') {
    $html = '
    <div class="mb-3">
        <label for="edit_make" class="form-label">Make</label>
        <input type="text" class="form-control" id="edit_make" name="make" value="' . htmlspecialchars($car['make']) . '" required>
    </div>
    <div class="mb-3">
        <label for="edit_model" class="form-label">Model</label>
        <input type="text" class="form-control" id="edit_model" name="model" value="' . htmlspecialchars($car['model']) . '" required>
    </div>
    <div class="mb-3">
        <label for="edit_year" class="form-label">Year</label>
        <input type="number" class="form-control" id="edit_year" name="year" value="' . htmlspecialchars($car['year']) . '" min="1900" max="' . (date('Y') + 1) . '" required>
    </div>
    <div class="mb-3">
        <label for="edit_price" class="form-label">Price ($)</label>
        <input type="number" class="form-control" id="edit_price" name="price" value="' . htmlspecialchars($car['price']) . '" min="0" step="0.01" required>
    </div>
    <div class="mb-3">
        <label for="edit_image" class="form-label">Image</label>';
    
    if ($car['image_path']) {
        $html .= '
        <div class="mb-2">
            <img src="' . htmlspecialchars($car['image_path']) . '" class="img-thumbnail" style="max-height: 150px;">
        </div>';
    }
    
    $html .= '
        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
        <div class="form-text">Leave empty to keep current image</div>
    </div>';
    
    echo $html;
}
?>