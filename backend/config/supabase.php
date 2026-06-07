<?php

return [

    'url' => env('SUPABASE_URL'),

    'anon_key' => env('SUPABASE_ANON_KEY'),

    'jwt_secret' => env('SUPABASE_JWT_SECRET'),

    'db_schema' => env('SUPABASE_DB_SCHEMA', 'public'),

];