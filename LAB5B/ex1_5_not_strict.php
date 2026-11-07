<?php
function addNumbers(int $a, int $b) {
    return $a + $b;
}
echo addNumbers(5, "5");
// since strict is NOT enabled the string "5" is changed to int(5), and it will return 10
// Note: the lab sheet uses "5 days". That only worked in PHP 7 - since PHP 8,
// "5 days" gives a TypeError even without strict mode, so "5" is used here.
?>
