<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Leadership "Message Desk" entries (Vice Chancellor, Director, Principal, ...)
 * shown on the home page and manageable from the admin panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leadership_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('organization')->nullable();
            $table->text('message');
            $table->string('photo')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the three default leaders so the Message Desk is populated out of the box.
        $now = now();
        DB::table('leadership_messages')->insert([
            [
                'name' => 'Vice Chancellor', 'designation' => 'Vice Chancellor',
                'organization' => 'Karakoram International University',
                'message' => 'It gives me immense pleasure to welcome you to Jinnah Degree College Astore. As an affiliated institution, the college upholds the highest standards of academic excellence and character building. I am confident our students will emerge as capable, responsible citizens ready to serve the nation.',
                'photo' => null, 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Directorate of Colleges', 'designation' => 'Director of Colleges',
                'organization' => 'Government of Gilgit-Baltistan',
                'message' => 'The Government of Gilgit-Baltistan is deeply committed to expanding access to quality higher education across all districts, including the valleys of Astore. Jinnah Degree College Astore represents our vision of bringing world-class education to the doorstep of every deserving student in the region.',
                'photo' => null, 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => DB::table('college_settings')->where('key', 'college_principal')->value('value') ?: 'Arif Ali',
                'designation' => 'Principal', 'organization' => 'Jinnah Degree College Astore',
                'message' => 'On behalf of the faculty and staff, I warmly welcome you to our institution. We are committed to providing quality education, discipline and a supportive environment that nurtures every student to realise their full potential — preparing them for success in higher education and professional life.',
                'photo' => null, 'sort_order' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('leadership_messages');
    }
};
