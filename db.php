<?php
require_once __DIR__ . '/Docker.php';

$docker = new Docker();
$conn = $docker->getPdo();