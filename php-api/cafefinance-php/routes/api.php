<?php

return [
    'GET /' => static fn (array $_controllers) => [
        'status' => 'success',
        'message' => 'PHP API funcionando',
        'endpoints' => [
            '/health',
            '/users',
        ],
    ],
    'GET /health' => static fn (array $controllers) => $controllers['health']->index(),
    'GET /users' => static fn (array $controllers) => $controllers['users']->index(),
];
