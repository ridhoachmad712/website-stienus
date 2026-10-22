<?php

return [
    // Keep this address reachable for the lifetime of printed diplomas.
    'verification_base_url' => env('DIPLOMA_VERIFICATION_URL', env('APP_URL', 'http://localhost')),
];
