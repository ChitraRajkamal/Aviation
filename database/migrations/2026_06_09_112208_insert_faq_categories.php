<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('faq_categories')->insert([
            [
                'id' => 1,
                'name' => 'Cabin Crew / Air Hostess',
                'slug' => 'cabin-crew-air-hostess',
                'description' => 'Learn about Cabin Crew careers, eligibility, grooming standards, airline interviews, training requirements, salary expectations, and placement opportunities.',
                'sort_order' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Airport Operations',
                'slug' => 'airport-operations',
                'description' => 'Explore Airport Operations, passenger services, baggage handling, check-in procedures, airport management, and aviation ground operations careers.',
                'sort_order' => 2,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'CPL / PPL Pilot Training',
                'slug' => 'cpl-ppl-pilot-training',
                'description' => 'Find answers about CPL and PPL pilot training, DGCA requirements, flying hours, medical fitness, ground school subjects, and pilot careers.',
                'sort_order' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Drone Training',
                'slug' => 'drone-training',
                'description' => 'Learn about drone technology, DGCA drone regulations, UAV operations, flight planning, practical training, and drone career opportunities.',
                'sort_order' => 4,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Air Cargo Operations',
                'slug' => 'air-cargo-operations',
                'description' => 'Understand air cargo operations, logistics, cargo documentation, warehouse management, customs procedures, and cargo industry careers.',
                'sort_order' => 5,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Travel & Tourism',
                'slug' => 'travel-tourism',
                'description' => 'Explore travel and tourism careers, airline ticketing, reservation systems, visa procedures, destination management, and hospitality services.',
                'sort_order' => 6,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'RTR (Aero)',
                'slug' => 'rtr-aero',
                'description' => 'Get information about RTR (Aero) preparation, aviation phraseology, ATC communication, radio telephony procedures, and pilot licensing requirements.',
                'sort_order' => 7,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Customer Service Agent',
                'slug' => 'customer-service-agent',
                'description' => 'Learn customer service skills for aviation, passenger handling, check-in procedures, airline reservations, and airport customer support careers.',
                'sort_order' => 8,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Airport Security',
                'slug' => 'airport-security',
                'description' => 'Discover airport security procedures, passenger screening, baggage inspection, aviation safety regulations, and airport security career opportunities.',
                'sort_order' => 9,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
