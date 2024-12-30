<?php
$templates = array_diff(scandir('../templates'), array('..', '.'));
$templates = array_map(function($template) {
    return pathinfo($template, PATHINFO_FILENAME);
}, $templates);

header('Content-Type: application/json');
echo json_encode($templates);
?>
