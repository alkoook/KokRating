<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Action',
            'Adventure',
            'Animation',
            'Biography',
            'Comedy',
            'Crime',
            'Documentary',
            'Drama',
            'Family',
            'Fantasy',
            'History',
            'Horror',
            'Music',
            'Musical',
            'Mystery',
            'Romance',
            'Sci-Fi',
            'Sport',
            'Thriller',
            'War',
            'Western',
            'Reality-TV',
            'Talk-Show',
            'Game-Show',
            'Tragedy',
            'Noir',
            'Superhero',
            'Fantasy-Comedy',
            'Psychological',
            'Suspense',
            'Paranormal',
            'Cyberpunk',
            'Steampunk',
            'Epic',
            'Dark-Comedy',
            'Satire',
            'Post-Apocalyptic',
            'Utopian',
            'Dystopian',
            'Teen',
            'Romantic-Comedy',
            'Survival',
            'Political',
            'Educational',
            'Experimental',
            'Philosophical',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
            ]);
        }
    }
}