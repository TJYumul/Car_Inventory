<?php
include('database.php');

$requestData = $_REQUEST;

$columns = array(
    0 => 'image_path',
    1 => 'make',
    2 => 'model',
    3 => 'year',
    4 => 'price'
);

$sql = "SELECT * FROM cars";
$query = $pdo->query($sql);
$totalData = $query->rowCount();
$totalFiltered = $totalData;

if (!empty($requestData['search']['value'])) {
    $sql .= " WHERE make LIKE :search OR model LIKE :search OR year LIKE :search OR price LIKE :search";
    $stmt = $pdo->prepare($sql);
    $searchValue = "%" . $requestData['search']['value'] . "%";
    $stmt->bindValue(':search', $searchValue, PDO::PARAM_STR);
    $stmt->execute();
    $totalFiltered = $stmt->rowCount();
} else {
    $stmt = $pdo->query($sql);
}

$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
$stmt = $pdo->prepare($sql);
if (!empty($requestData['search']['value'])) {
    $stmt->bindValue(':search', $searchValue, PDO::PARAM_STR);
}
$stmt->execute();

$data = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $nestedData = array();
    $nestedData['image_path'] = $row['image_path'];
    $nestedData['make'] = $row['make'];
    $nestedData['model'] = $row['model'];
    $nestedData['year'] = $row['year'];
    $nestedData['price'] = $row['price'];
    $nestedData['id'] = $row['id'];
    $data[] = $nestedData;
}

$json_data = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

echo json_encode($json_data);
?>