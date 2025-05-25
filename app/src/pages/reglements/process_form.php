<?php
// process_form.php

// Capture the incoming JSON data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is received properly
if ($data) {
  // Set the path where the JSON file will be saved
  $chemin = $_SERVER['DOCUMENT_ROOT'];
  $jsonFilePath = $chemin . '/src/pages/reglements/form_data_' . time() . '.json';  // You can modify this path/filename

  // Convert the received form data back to JSON string
  $jsonContent = json_encode($data, JSON_PRETTY_PRINT);

  // Write the JSON content to a file
  if (file_put_contents($jsonFilePath, $jsonContent)) {
    // Success: Now you can also insert the data into the MySQL database if needed

    // Example MySQL connection (adjust with your credentials)
    // $mysqli = new mysqli('localhost', 'username', 'password', 'database');
    // Example MySQL query to save data (adjust with your table/column names)
    // $bank = $data['bank']; // Replace 'bank' with your actual input names
    // $payment_method = $data['payment_method']; // Example data field
    // $query = "INSERT INTO payments (bank, payment_method) VALUES ('$bank', '$payment_method')";
    // $mysqli->query($query);

    // Send a success response back to the frontend
    echo json_encode(['status' => 'success', 'message' => 'Form data processed and JSON file created!']);
  } else {
    // Error in saving the JSON file
    echo json_encode(['status' => 'error', 'message' => 'Failed to create JSON file']);
  }
} else {
  // No data received
  echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
