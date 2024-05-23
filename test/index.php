<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Rohmattaufik\Allocation\RoundRobbin;
$roundRobbin = new RoundRobbin();
$roundRobbin->setAllocation("User A", 2);
$roundRobbin->setAllocation("User B", 1);
$roundRobbin->setAllocation("User C", 3);
$roundRobbin->setAllocation("User D", 1);

$allocations = $roundRobbin->getAllocation();
foreach ($allocations as $allocation)
    echo "Allocation: ". $allocation->key ." - ". $allocation->weight ."<br>";

echo "<br>====================";
for ($idx = 1; $idx < 20; $idx++)
{
    $allocate = $roundRobbin->allocate();
    echo "<br>Allocation ".$idx." ". $allocate->key ."<br>";

    foreach ($allocations as $allocation)
        echo "<br>Remaining: ". $allocation->key ." - ". $allocation->remaining ."";
    echo "<br>-----";
}