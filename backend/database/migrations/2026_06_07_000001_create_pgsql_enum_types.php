<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DO \$\$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_role') THEN
                    CREATE TYPE user_role AS ENUM ('super_admin', 'admin', 'editor', 'media', 'member');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'testimonial_status') THEN
                    CREATE TYPE testimonial_status AS ENUM ('pending', 'approved', 'rejected');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'order_status') THEN
                    CREATE TYPE order_status AS ENUM ('pending', 'paid', 'failed', 'shipped', 'completed', 'cancelled', 'refunded');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'donation_type') THEN
                    CREATE TYPE donation_type AS ENUM ('one_time', 'recurring');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'donation_category') THEN
                    CREATE TYPE donation_category AS ENUM ('general', 'tithe', 'mission', 'building', 'youth', 'other');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'subscriber_status') THEN
                    CREATE TYPE subscriber_status AS ENUM ('pending_confirmation', 'active', 'unsubscribed', 'bounced', 'complained');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'email_log_type') THEN
                    CREATE TYPE email_log_type AS ENUM ('daily_bread', 'announcement', 'welcome', 'subscription_confirmation');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'service_format') THEN
                    CREATE TYPE service_format AS ENUM ('in_person', 'online', 'hybrid');
                END IF;

                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'leader_category') THEN
                    CREATE TYPE leader_category AS ENUM ('pastor', 'elder', 'deacon', 'staff', 'ministry_head');
                END IF;
            END
            \$\$;
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP TYPE IF EXISTS leader_category;
            DROP TYPE IF EXISTS service_format;
            DROP TYPE IF EXISTS email_log_type;
            DROP TYPE IF EXISTS subscriber_status;
            DROP TYPE IF EXISTS donation_category;
            DROP TYPE IF EXISTS donation_type;
            DROP TYPE IF EXISTS order_status;
            DROP TYPE IF EXISTS testimonial_status;
            DROP TYPE IF EXISTS user_role;
        ");
    }
};