<?php
include 'SplWorker.php';

echo "Link to path";
$path = readline("path:");

$spl = new SplWorker();
echo $spl->getSumNumbers($path);
