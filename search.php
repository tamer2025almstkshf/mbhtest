<?php
    include_once 'connection.php';
    include_once 'login_check.php';

$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT id, arname FROM client WHERE arname LIKE ? OR id LIKE ?";
$stmt = $conn->prepare($sql);

$searchTerm = "%" . $searchQuery . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div onclick="selectOption(\'' . $row['arname'] . '\')">' . $row['arname'] . ' # ' . $row['id'] . '</div>';
    }
} else {
    echo '<div>No results found</div>';
}

$stmt->close();
$conn->close();
?>
