<?php
declare(strict_types=1);
function formatDollarAmount(float  $amount):string{

$isNegative = $amount<0;
//we handle negative numbers before concatenating it with $ sign and actual format.
return ($isNegative ? '-':'') . '$' . number_format(abs($amount),2);
}