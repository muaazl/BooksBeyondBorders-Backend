<?php // search.php

require_once __DIR__ . '/../include/db_connect.php'; // Database connection

// Get the search query from the URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Sanitize the query (important!)
$query = htmlspecialchars($query);

// Prepare the SQL query (using prepared statements to prevent SQL injection)
$sql = "SELECT id, title, author, price, image FROM products WHERE title LIKE ? OR author LIKE ?";
$stmt = $conn->prepare($sql);

// Bind the parameter (using wildcards for partial matches)
$search_term = "%" . $query . "%"; // Add wildcards for partial matches

// "ss" indicates two string parameters
$stmt->bind_param("ss", $search_term, $search_term);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Create an array to store the results
$products = array();

// Fetch the results and add them to the array
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Close the statement
$stmt->close();

// Close the connection (not strictly necessary here, but good practice)
// $conn->close();  // Don't close here, let the calling page close if it needs it.

// Set the content type to JSON
header('Content-Type: application/json');

// Return the results as JSON
echo json_encode($products);

?>