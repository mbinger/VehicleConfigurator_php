<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\table;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123qwe'),
        ]);

        $createdAt = Carbon::now();

        $vendorVolkswagenId = DB::table('vendors')->insertGetId([
            'name' => 'Volkswagen',
            'created_at' => $createdAt
        ]);

        $vendorMazdaId = DB::table('vendors')->insertGetId([
            'name' => 'Mazda',
            'created_at' => $createdAt
        ]);

        $vendorBmwId = DB::table('vendors')->insertGetId([
            'name' => 'BWM',
            'created_at' => $createdAt
        ]);

        $fuelTypeDieselId = DB::table('fuel_types')->insertGetId([
            'name' => 'Diesel',
            'eco_class' => 10,
            'created_at' => $createdAt
        ]);

        $fuelTypeGasolineId = DB::table('fuel_types')->insertGetId([
            'name' => 'Gasoline',
            'eco_class' => 20,
            'created_at' => $createdAt
        ]);

        $fuelTypeHybridId = DB::table('fuel_types')->insertGetId([
            'name' => 'Hybrid',
            'eco_class' => 30,
            'created_at' => $createdAt
        ]);

        $motorDiesel1 = DB::table('motors')->insertGetId([
            'name' => 'Diesel 1.8',
            'fuel_type_id' => $fuelTypeDieselId,
            'price' => 2000,
            'created_at' => $createdAt
        ]);

        $motorDiesel2 = DB::table('motors')->insertGetId([
            'name' => 'Diesel 3.5',
            'fuel_type_id' => $fuelTypeDieselId,
            'price' => 4000,
            'created_at' => $createdAt
        ]);

        $motorTdi1 = DB::table('motors')->insertGetId([
            'name' => 'TDI 1.8',
            'fuel_type_id' => $fuelTypeDieselId,
            'price' => 2200,
            'created_at' => $createdAt
        ]);

        $motorTdi2 = DB::table('motors')->insertGetId([
            'name' => 'TDI 3.5',
            'fuel_type_id' => $fuelTypeDieselId,
            'price' => 4200,
            'created_at' => $createdAt
        ]);

        $motorGasoline1 = DB::table('motors')->insertGetId([
            'name' => 'Gasoline 1.6',
            'fuel_type_id' => $fuelTypeGasolineId,
            'price' => 1500,
            'created_at' => $createdAt
        ]);

        $motorGasoline2 = DB::table('motors')->insertGetId([
            'name' => 'Gasoline 2.5',
            'fuel_type_id' => $fuelTypeGasolineId,
            'price' => 5000,
            'created_at' => $createdAt
        ]);

        $motorTsi1 = DB::table('motors')->insertGetId([
            'name' => 'TSI 1.6',
            'fuel_type_id' => $fuelTypeGasolineId,
            'price' => 1700,
            'created_at' => $createdAt
        ]);

        $motorTsi2 = DB::table('motors')->insertGetId([
            'name' => 'TSI 2.0',
            'fuel_type_id' => $fuelTypeGasolineId,
            'price' => 5200,
            'created_at' => $createdAt
        ]);

        $motorHybrid1 = DB::table('motors')->insertGetId([
            'name' => 'Hybrid 100 PS',
            'fuel_type_id' => $fuelTypeHybridId,
            'price' => 3000,
            'created_at' => $createdAt
        ]);

        $motorHybrid2 = DB::table('motors')->insertGetId([
            'name' => 'Hybrid 150 PS',
            'fuel_type_id' => $fuelTypeHybridId,
            'price' => 4000,
            'created_at' => $createdAt
        ]);

        $motorSkyactivD1 = DB::table('motors')->insertGetId([
            'name' => 'Skyactiv-D 1.8',
            'fuel_type_id' => $fuelTypeDieselId,
            'price' => 2100,
            'created_at' => $createdAt
        ]);

        $motorSkyactivD2 = DB::table('motors')->insertGetId([
            'name' => 'Skyactiv-D 3.5',
            'fuel_type_id' => $fuelTypeDieselId,
            'price' => 4100,
            'created_at' => $createdAt
        ]);

        $motorSkyactivG1 = DB::table('motors')->insertGetId([
            'name' => 'Skyactiv-G 2.0',
            'fuel_type_id' => $fuelTypeGasolineId,
            'price' => 2600,
            'created_at' => $createdAt
        ]);

        $motorSkyactivG2 = DB::table('motors')->insertGetId([
            'name' => 'Skyactiv-G 2.5',
            'fuel_type_id' => $fuelTypeGasolineId,
            'price' => 2800,
            'created_at' => $createdAt
        ]);

        $motorSkyactivX1 = DB::table('motors')->insertGetId([
            'name' => 'Skyactiv-X 2.0',
            'fuel_type_id' => $fuelTypeHybridId,
            'price' => 3000,
            'created_at' => $createdAt
        ]);

        #golf

        $carVw1 = DB::table('cars')->insertGetId([
           'name' => 'Golf',
           'vendor_id' => $vendorVolkswagenId,
           'price' => 20000,
           'created_at' => $createdAt
        ]);

        foreach ([$motorTdi1, $motorTsi1] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carVw1,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        #passat

        $carVw2 = DB::table('cars')->insertGetId([
            'name' => 'Passat',
            'vendor_id' => $vendorVolkswagenId,
            'price' => 30000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorTdi1, $motorTdi2, $motorTsi1, $motorTsi2] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carVw2,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carVw3 = DB::table('cars')->insertGetId([
            'name' => 'Tiguan',
            'vendor_id' => $vendorVolkswagenId,
            'price' => 40000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorTdi1, $motorTdi2, $motorTsi1, $motorTsi2, $motorHybrid1, $motorHybrid2] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carVw3,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carMazda1 = DB::table('cars')->insertGetId([
            'name' => '3',
            'vendor_id' => $vendorMazdaId,
            'price' => 15000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorSkyactivG1, $motorSkyactivD1] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carMazda1,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carMazda2 = DB::table('cars')->insertGetId([
            'name' => '6',
            'vendor_id' => $vendorMazdaId,
            'price' => 20000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorSkyactivG1, $motorSkyactivG2, $motorSkyactivD1, $motorSkyactivD2] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carMazda2,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carMazda3 = DB::table('cars')->insertGetId([
            'name' => 'CX-30',
            'vendor_id' => $vendorMazdaId,
            'price' => 30000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorSkyactivG1, $motorSkyactivG2, $motorSkyactivD1, $motorSkyactivD2, $motorSkyactivX1] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carMazda3,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carMazda4 = DB::table('cars')->insertGetId([
            'name' => 'CX-5',
            'vendor_id' => $vendorMazdaId,
            'price' => 35000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorSkyactivD1, $motorSkyactivD2, $motorSkyactivX1] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carMazda4,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carBmw1 = DB::table('cars')->insertGetId([
            'name' => '3',
            'vendor_id' => $vendorBmwId,
            'price' => 30000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorDiesel1, $motorGasoline1, $motorHybrid1] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carBmw1,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carBmw2 = DB::table('cars')->insertGetId([
            'name' => '5',
            'vendor_id' => $vendorBmwId,
            'price' => 50000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorDiesel1, $motorDiesel2, $motorGasoline1, $motorGasoline2, $motorHybrid1, $motorHybrid2] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carBmw2,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carBmw3 = DB::table('cars')->insertGetId([
            'name' => 'X5',
            'vendor_id' => $vendorBmwId,
            'price' => 70000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorDiesel2, $motorGasoline2, $motorHybrid1, $motorHybrid2] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carBmw3,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        $carBmw4 = DB::table('cars')->insertGetId([
            'name' => 'M5',
            'vendor_id' => $vendorBmwId,
            'price' => 80000,
            'created_at' => $createdAt
        ]);

        foreach ([$motorGasoline2, $motorHybrid2] as $motorId)
        {
            DB::table('car_motors')->insert([
                'car_id' => $carBmw4,
                'motor_id' => $motorId,
                'created_at' => $createdAt
            ]);
        }

        DB::table('options')->insert([
            'name' => 'A/C',
            'price' => 1000,
            'created_at' => $createdAt
        ]);

        DB::table('options')->insert([
            'name' => 'AT',
            'price' => 3000,
            'created_at' => $createdAt
        ]);

        DB::table('options')->insert([
            'name' => 'Alloy wheels',
            'price' => 1000,
            'created_at' => $createdAt
        ]);

        DB::table('options')->insert([
            'name' => 'Subwoofer',
            'price' => 500,
            'created_at' => $createdAt
        ]);

        DB::table('options')->insert([
            'name' => 'Navigation',
            'price' => 200,
            'created_at' => $createdAt
        ]);

        DB::table('customers')->insert([
            'first_name' => 'Max',
            'last_name' => 'Musterman',
            'birthday' => '2001-01-01',
            'created_at' => $createdAt
        ]);
    }
}
