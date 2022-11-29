<?php

namespace App\Seeders;

use App\Models\Articles\Comment;

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::truncate();

        foreach ($this->comments() as $comment) {
        	Comment::create($comment);
        }
    }

    public function comments()
    {
    	$faker = Faker::create();

    	return [
    		[
    			'user_id' => 1,
    			'article_id' => 1,
    			'comment' => $faker->text
    		],
    		[
    			'user_id' => 2,
    			'article_id' => 1,
    			'comment' => $faker->text
    		],
    		[
    			'user_id' => 1,
    			'article_id' => 2,
    			'comment' => $faker->text
    		],
    		[
    			'user_id' => 3,
    			'article_id' => 2,
    			'comment' => $faker->text
    		],
    		[
    			'user_id' => 4,
    			'article_id' => 3,
    			'comment' => $faker->text
    		],
    		[
    			'user_id' => 5,
    			'article_id' => 3,
    			'comment' => $faker->text
    		],
    	];
    }
}
