<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reward;
use Carbon\Carbon;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the reward data
        $rewards = [
            [
                'reward_name' => 'Recycle Plastic Chair (Green)',
                'reward_description' => 'Eco-friendly plastic chair made from recycled materials.',
                'reward_duration_date' => Carbon::create(2024, 12, 31, 17, 29, 00)->format('Y-m-d H:i:s'),
                'reward_status' => 'available',
                'reward_quantity' => 20,
                'reward_point_required' => 40,
                'reward_image' => '1734448489.jpg',
            ],
            [
                'reward_name' => 'Eco Plastic Bottle',
                'reward_description' => 'Eco Plastic Bottle for drinking use',
                'reward_duration_date' => Carbon::create(2024, 12, 31, 17, 29, 00)->format('Y-m-d H:i:s'),
                'reward_status' => 'available',
                'reward_quantity' => 50,
                'reward_point_required' => 12,
                'reward_image' => '1734448504.png',
            ],
        ];

        // Seed the rewards
        foreach ($rewards as $rewardData) {
            Reward::updateOrCreate(
                ['reward_name' => $rewardData['reward_name']], // Check by reward name
                $rewardData
            );
        }
    }
}
