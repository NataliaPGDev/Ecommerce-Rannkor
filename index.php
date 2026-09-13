<?php
session_start();
ob_start();

require_once __DIR__ . '/componentes/header.php';
require_once __DIR__ . '/router.php';
require_once __DIR__ . '/componentes/footer.php';

ob_end_flush();
