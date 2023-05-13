<?php

require_once(__DIR__ . '/../../../../config.php');
header('Content-Type: text/css');

if (!$USER || !$USER->id) {
    exit;
}

require_once(__DIR__ . '/../../lib.php');
require_once(__DIR__ . '/accessibility_textcolour.php');

$option = new local_accessibility\options\textcolour();

$userconfig = $option->getuserconfig();
if (!$userconfig) {
    exit;
}

echo "body.accessibility-textcolour, body.accessibility-textcolour * { color: {$userconfig}; }";
