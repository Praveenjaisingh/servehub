<?php

http_response_code(422);
header('Content-Type: application/json');
header('X-Debug-Marker: raw-php-test');

echo json_encode([
    'status' => false,
    'message' => 'This is a raw PHP test response, not Laravel.',
]);