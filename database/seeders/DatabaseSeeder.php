<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Medical;
use App\Models\User;
use App\Models\Society;
use App\Models\Regional;
use App\Models\Spot;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $region1 = new Regional();
        $region1->province = 'Jawa Barat';
        $region1->district = 'Sukabumi';
        $region1->save();

        $user1 = new User();
        $user1->username = 'dokter1';
        $user1->password = bcrypt('dokter1');
        $user1->save();

        $user2 = new User();
        $user2->username = 'officer1';
        $user2->password = bcrypt('officer1');
        $user2->save();

        $spot1 = new Spot();
        $spot1->regional_id = 1;
        $spot1->name = 'RS Hermina';
        $spot1->address = 'Sukaraja';
        $spot1->serve = 1234;
        $spot1->capacity = 12;
        $spot1->save();

        $medical1 = new Medical();
        $medical1->name = 'dokter1';
        $medical1->role = 'doctor';
        $medical1->user_id = 1;
        $medical1->spot_id = 1;
        $medical1->save();

        $medical1 = new Medical();
        $medical1->name = 'officer1';
        $medical1->role = 'officer';
        $medical1->user_id = 1;
        $medical1->spot_id = 1;
        $medical1->save();

        $society1 = new Society();
        $society1->id_card_number = '12345';
        $society1->password = bcrypt('12345');
        $society1->name = 'Asep';
        $society1->born_date = '2000-12-12 12:12:12';
        $society1->gender = 'male';
        $society1->address = 'Cipoho';
        $society1->regional_id = 1;
        $society1->save();
    }
}
