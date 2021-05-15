<?php

declare(strict_types=1);

require_once '../config/bootstrap.php';

$controller = new Tickets\Controller\TicketsController();
$controller->route($_GET['route'] ?? null);
