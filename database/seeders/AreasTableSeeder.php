<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['name' => 'Warehouse', 'description' => 'Storage area for goods and materials.'],
            ['name' => 'Production Line', 'description' => 'Area where products are assembled.'],
            ['name' => 'Quality Control', 'description' => 'Area for inspecting and testing products.'],
            ['name' => 'Packaging', 'description' => 'Area where products are packaged for shipment.'],
            ['name' => 'Loading Dock', 'description' => 'Area for loading and unloading goods.'],
            ['name' => 'Maintenance', 'description' => 'Area for equipment and machinery maintenance.'],
            ['name' => 'Office', 'description' => 'Administrative and management offices.'],
            ['name' => 'Research and Development', 'description' => 'Area for developing new products.'],
            ['name' => 'Cafeteria', 'description' => 'Area where employees have their meals.'],
            ['name' => 'Reception', 'description' => 'Area where visitors are received.'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
