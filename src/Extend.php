<?php

namespace forumez\SupabaseAuth;

use Flarum\Extend;
use Illuminate\Support\Arr;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/../js/dist/forum.js'),

    (new Extend\Routes('api'))
        ->post('/supabase/login', 'supabase.login', SupabaseAuthController::class),
];
