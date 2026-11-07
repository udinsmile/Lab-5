<?php
// Shared helpers for the book pages

// Categories offered in the Add/Edit form
$CATEGORIES = ["Computer Science", "Engineering", "Mathematics", "Science", "Fiction", "Reference", "Other"];

// Escape text before printing it in HTML (prevents XSS)
function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, "UTF-8");
}

// Validate the book form. Returns an array of errors (empty = valid).
function validate_book($book, $categories) {
    $errors = [];

    if ($book["title"] === "") {
        $errors["title"] = "Title is required.";
    }
    if ($book["author"] === "") {
        $errors["author"] = "Author is required.";
    }
    if ($book["publish_year"] === "") {
        $errors["publish_year"] = "Publish year is required.";
    } elseif (!preg_match('/^\d{4}$/', $book["publish_year"])) {
        $errors["publish_year"] = "Publish year must be a 4-digit number (e.g. 2021).";
    } elseif ((int)$book["publish_year"] > (int)date("Y")) {
        $errors["publish_year"] = "Publish year cannot be in the future.";
    }
    if ($book["category"] === "") {
        $errors["category"] = "Category is required.";
    } elseif (!in_array($book["category"], $categories, true)) {
        $errors["category"] = "Please choose a valid category.";
    }

    return $errors;
}

// Read the book fields from the submitted form (trimmed)
function book_from_post() {
    return [
        "title"        => trim($_POST["title"] ?? ""),
        "author"       => trim($_POST["author"] ?? ""),
        "publish_year" => trim($_POST["publish_year"] ?? ""),
        "category"     => trim($_POST["category"] ?? ""),
    ];
}
?>
