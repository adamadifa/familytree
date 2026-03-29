<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FamilyMember;
use App\Models\Relationship;
use Carbon\Carbon;

class FamilyTreeSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Relationship::truncate();
        FamilyMember::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@family.com'],
            [
                'name' => 'Admin Keluarga',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. ROOT GENERATION (Ayah & Ibu)
        $ayah = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Supena',
            'last_name' => 'Kusumah',
            'gender' => 'M',
            'birth_date' => '1960-05-15',
        ]);

        $ibu = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Siti',
            'last_name' => 'Aminah',
            'gender' => 'F',
            'birth_date' => '1965-08-20',
        ]);

        // Pernikahan Ayah & Ibu
        Relationship::create([
            'person_a_id' => $ayah->id,
            'person_b_id' => $ibu->id,
            'relationship_type' => 'spouse',
            'created_by' => $user->id,
        ]);

        // 3. GENERATION 2 (3 Anak dari Ayah & Ibu)
        // Anak 1 (Laki-laki)
        $anak1 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Budi',
            'last_name' => 'Supena',
            'gender' => 'M',
            'birth_date' => '1988-02-10',
        ]);
        
        // Relasi Anak 1 ke Orang Tua
        Relationship::create(['person_a_id' => $ayah->id, 'person_b_id' => $anak1->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
        Relationship::create(['person_a_id' => $ibu->id, 'person_b_id' => $anak1->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);

        // Anak 2 (Perempuan)
        $anak2 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Sartika',
            'last_name' => 'Supena',
            'gender' => 'F',
            'birth_date' => '1990-11-25',
        ]);
        Relationship::create(['person_a_id' => $ayah->id, 'person_b_id' => $anak2->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
        Relationship::create(['person_a_id' => $ibu->id, 'person_b_id' => $anak2->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);

        // Anak 3 (Laki-laki)
        $anak3 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Candra',
            'last_name' => 'Supena',
            'gender' => 'M',
            'birth_date' => '1995-07-08',
        ]);
        Relationship::create(['person_a_id' => $ayah->id, 'person_b_id' => $anak3->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
        Relationship::create(['person_a_id' => $ibu->id, 'person_b_id' => $anak3->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);

        // 4. GENERATION 3 (Keluarga Anak 1)
        // Pasangan Anak 1
        $istriAnak1 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Dewi',
            'last_name' => 'Lestari',
            'gender' => 'F',
            'birth_date' => '1990-04-12',
        ]);
        
        // Pernikahan Anak 1 & Istrinya (Pernikahan Pertama yg Berujung Cerai)
        Relationship::create([
            'person_a_id' => $anak1->id,
            'person_b_id' => $istriAnak1->id,
            'relationship_type' => 'spouse',
            'metadata' => json_encode(['status' => 'divorced']),
            'created_by' => $user->id,
        ]);

        // 2 Cucu dari Anak 1
        $cucu1 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Doni',
            'last_name' => 'Supena',
            'gender' => 'M',
            'birth_date' => '2015-09-01',
        ]);
        Relationship::create(['person_a_id' => $anak1->id, 'person_b_id' => $cucu1->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
        Relationship::create(['person_a_id' => $istriAnak1->id, 'person_b_id' => $cucu1->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);

        $cucu2 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Dina',
            'last_name' => 'Supena',
            'gender' => 'F',
            'birth_date' => '2018-03-14',
        ]);
        Relationship::create(['person_a_id' => $anak1->id, 'person_b_id' => $cucu2->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
        Relationship::create(['person_a_id' => $istriAnak1->id, 'person_b_id' => $cucu2->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);

        // 5. Kasus Kompleks (Perceraian & Pernikahan Kedua)
        
        // Istri Kedua Anak 1 (Budi bercerai dengan Dewi, menikah dengan Susi)
        $istriAnak1_2 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Susi',
            'last_name' => 'Susanti',
            'gender' => 'F',
            'birth_date' => '1992-06-18',
        ]);
        Relationship::create([
            'person_a_id' => $anak1->id,
            'person_b_id' => $istriAnak1_2->id,
            'relationship_type' => 'spouse',
            'created_by' => $user->id,
        ]);

        // 1 Anak dari Budi & Susi
        $cucu3 = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Bagas',
            'last_name' => 'Supena',
            'gender' => 'M',
            'birth_date' => '2022-10-05',
        ]);
        Relationship::create(['person_a_id' => $anak1->id, 'person_b_id' => $cucu3->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
        Relationship::create(['person_a_id' => $istriAnak1_2->id, 'person_b_id' => $cucu3->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);

        // Suami Sebelumnya Susi (Mantan Suami Susi yang Berujung Cerai)
        $mantanSusi = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Herman',
            'last_name' => 'Syah',
            'gender' => 'M',
            'birth_date' => '1990-08-08',
        ]);
        Relationship::create([
            'person_a_id' => $istriAnak1_2->id,
            'person_b_id' => $mantanSusi->id,
            'relationship_type' => 'spouse',
            'metadata' => json_encode(['status' => 'divorced']),
            'created_by' => $user->id,
        ]);

        // Anak Bawaan Susi (Anak Kandung Susi, Anak Tiri Budi)
        $anakBawaan = FamilyMember::create([
            'created_by' => $user->id,
            'first_name' => 'Anton',
            'last_name' => 'Syah',
            'gender' => 'M',
            'birth_date' => '2019-11-20',
        ]);
        Relationship::create(['person_a_id' => $istriAnak1_2->id, 'person_b_id' => $anakBawaan->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
        Relationship::create(['person_a_id' => $mantanSusi->id, 'person_b_id' => $anakBawaan->id, 'relationship_type' => 'parent_child', 'created_by' => $user->id]);
    }
}
