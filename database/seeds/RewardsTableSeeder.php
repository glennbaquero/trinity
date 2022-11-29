<?php

namespace App\Seeders;

use App\Models\Rewards\Reward;
use App\Models\Rewards\Sponsorship;
use App\Models\Rewards\RewardCategory;
use Illuminate\Database\Seeder;

class RewardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'CME',
            ],
        ];

        $sponsorships = [
            ['name' => 'Registration', 'points' => '100', 'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'],
            ['name' => 'Hotel Accommodation', 'points' => '200', 'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'],
            ['name' => 'Airfare', 'points' => '300', 'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'],
        ];

        $rewards = [
            [
                'name' => 'Trip to Europe',
                'description' => 'In medicine, a differential diagnosis is the distinguishing of a particular disease or conddition from others that present similar clinical features. Differential diagnostic procedures are used by physicians to diagnose the specific disease in a patient or, at least to eliminate any imminently life-threatening conditions. Often each individual option of a possible disease is called a differential diagnosis (e.g. acute bronchitis could be a differential diagnosis in the evaluation of a cough, even if the final diagnosis is commom cold)',
                'image_path' => 'rewards/reward1.jpg',
                'reward_category_id' => 1,
                'points' => 100,
                'expiry_date' => '03 Nov 2019',
            ],
            [
                'name' => 'Trip to Japan',
                'description' => 'In medicine, a differential diagnosis is the distinguishing of a particular disease or conddition from others that present similar clinical features. Differential diagnostic procedures are used by physicians to diagnose the specific disease in a patient or, at least to eliminate any imminently life-threatening conditions. Often each individual option of a possible disease is called a differential diagnosis (e.g. acute bronchitis could be a differential diagnosis in the evaluation of a cough, even if the final diagnosis is commom cold)',
                'image_path' => 'rewards/reward2.jpg',
                'reward_category_id' => 1,
                'points' => 200,
                'expiry_date' => '03 Nov 2019',
            ],
            [
                'name' => 'Trip to Korea',
                'description' => 'In medicine, a differential diagnosis is the distinguishing of a particular disease or conddition from others that present similar clinical features. Differential diagnostic procedures are used by physicians to diagnose the specific disease in a patient or, at least to eliminate any imminently life-threatening conditions. Often each individual option of a possible disease is called a differential diagnosis (e.g. acute bronchitis could be a differential diagnosis in the evaluation of a cough, even if the final diagnosis is commom cold)',
                'image_path' => 'rewards/reward3.jpg',
                'reward_category_id' => 1,
                'points' => 300,
                'expiry_date' => '03 Nov 2019',
            ]
        ];

        foreach($categories as $category) {
            RewardCategory::create($category);
        }

        foreach($rewards as $reward) {
            Reward::create($reward);
        }

        foreach($sponsorships as $sponsorship) {
            Sponsorship::create($sponsorship);
        }

        Reward::find(1)->sponsorships()->attach(Sponsorship::all()->random(2)->pluck('id'));
        Reward::find(2)->sponsorships()->attach(Sponsorship::all()->random(2)->pluck('id'));
    }
}
