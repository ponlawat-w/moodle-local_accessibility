<?php

require_once(__DIR__ . '/../../../../config.php');
header('Content-Type: text/css');

if (!$USER || !$USER->id) {
    exit;
}

require_once(__DIR__ . '/../../lib.php');
require_once(__DIR__ . '/accessibility_backgroundcolour.php');

$option = new local_accessibility\options\backgroundcolour();

$userconfig = $option->getuserconfig();
if (!$userconfig) {
    exit;
}

echo "body.accessibility-backgroundcolour, body.accessibility-backgroundcolour * { background-color: {$userconfig}; }";
