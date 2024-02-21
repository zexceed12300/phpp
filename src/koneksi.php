<?php

session_start();

$dsn = 'mysql:host=localhost;dbname=gallery';
$username = 'root';
$password = '';

try {
	$dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
	die("Gagal terhubung ke database");
}

function redirect($lokasi)
{
	header("Location: $lokasi");
}



function time_elapsed_string($datetime)
{
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$interval = $now->diff($ago);

	$eta = array(
		'years' => $interval->y,
		'months' => $interval->m,
		'days' => $interval->d
	);

	foreach ($eta as $key => $value) {
		if ($value > 0) {
			return "$value $key ago";
		}
	}
	return "A short time ago";
}

function validate($postData, $rules)
{
	$errors = "";
	$sanitizedData = [];

	// Sanitize input data
	foreach ($postData as $field => $value) {
		$sanitizedData[$field] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}

	// Apply validation rules
	foreach ($rules as $field => $rule) {
		$rulesArray = explode('|', $rule);
		$input = $sanitizedData[$field] ?? null; // Get sanitized value

		foreach ($rulesArray as $singleRule) {
			// Check for required fields
			if (strpos($singleRule, 'required') !== false && empty($input)) {
				$errors = "The $field field is required.";
			}

			// Check for string rule
			if ($singleRule === 'string' && !is_string($input)) {
				$errors = "$field haruslah karakter";
			}

			// Check for number rule
			if ($singleRule === 'number' && !is_numeric($input)) {
				$errors = "$field haruslah angka";
			}

			// if (strpos($singleRule, 'exists:') !== false) {
			// 	// Extract table and column name
			// 	$params = explode(':', $singleRule);
			// 	$table = $params[1];
			// 	$column = $params[2];

			// 	// Perform database query to check if the value exists in the column
			// 	// This is just a placeholder, you need to replace it with your actual database logic
			// 	// Assume you have a database connection stored in $dbConnection

			// 	$stmt = $dbh->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
			// 	$stmt->execute([$input]);
			// 	$count = $stmt->fetch();

			// 	if ($count === 0) {
			// 		$errors = "$field does not exist in $table.";
			// 	}
			// }

			// Add other validation rules as needed
			// Example: Check for a maximum length
			if (strpos($singleRule, 'max:') !== false) {
				$max = explode(':', $singleRule)[1];
				if (strlen($input) > $max) {
					$errors = "$field besar dari $max karakter";
				}
			}

			if (strpos($singleRule, 'min:') !== false) {
				$min = explode(':', $singleRule)[1];
				if (strlen($input) < $min) {
					$errors = "$field haruslah lebih besar dari $min karakter";
				}
			}

			// Add more validation rules as required
			// Example: Email validation
			if (strpos($singleRule, 'email') !== false && !filter_var($input, FILTER_VALIDATE_EMAIL)) {
				$errors = "$field haruslah email yang valid.";
			}
		}
	}

	$_SESSION['errors'] = $errors;

	if (!empty($errors)) {
		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit();
	}

	// Return the validation result along with errors and sanitized data
	return $sanitizedData;
}

function sanitizeInput($input)
{
	// Remove any HTML and PHP tags from the input
	$clean_input = strip_tags($input);

	// Convert special characters to HTML entities
	$clean_input = htmlspecialchars($clean_input, ENT_QUOTES, 'UTF-8');

	return $clean_input;
}
