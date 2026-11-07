<?php
declare(strict_types=1);   // strict type checking for every function in this file

// ---------------------------------------------------------------
// (a) Trim and sanitise one input value
// ---------------------------------------------------------------
function clean_input(string $data): string
{
    $data = stripslashes($data);                // remove backslashes
    $data = strip_tags($data);                  // remove HTML/PHP tags
    $data = preg_replace('/\s+/', ' ', $data);  // collapse repeated spaces
    $data = trim($data);                        // remove spaces at both ends
    return $data;
}

// ---------------------------------------------------------------
// (b) Average of an array of ratings (0.0 when the array is empty)
// ---------------------------------------------------------------
function calc_average(array $ratings): float
{
    if (count($ratings) === 0) {
        return 0.0;
    }
    $total = 0;
    foreach ($ratings as $r) {
        $total += (int)$r;
    }
    return round($total / count($ratings), 2);
}

// ---------------------------------------------------------------
// (c) Label for an average rating
// ---------------------------------------------------------------
function rating_label(float $avg): string
{
    if ($avg >= 4.5) {
        return "Excellent";
    } elseif ($avg >= 3.5) {
        return "Good";
    } elseif ($avg >= 2.5) {
        return "Fair";
    }
    return "Poor";
}

// ---------------------------------------------------------------
// (d) Add an error message to the array passed BY REFERENCE (&)
//     - the caller's array is changed directly, nothing is returned
// ---------------------------------------------------------------
function add_error(array &$errors, string $msg): void
{
    $errors[] = $msg;
}

// ---------------------------------------------------------------
// Extra helpers
// ---------------------------------------------------------------

// Courses offered in the drop-down (edit to match your faculty)
function get_courses(): array
{
    return ["BIC21203", "BIC21404", "BIC20904", "BIC10204"];
}

// Escape text before printing it in HTML
function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, "UTF-8");
}

// HTML for the error messages shown beside one field
function field_errors(array $messages): string
{
    $html = "";
    foreach ($messages as $m) {
        $html .= '<span class="error">' . e((string)$m) . '</span>';
    }
    return $html;
}
?>
