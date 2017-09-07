<?php
/**
 * Created by PhpStorm.
 * User: xd
 * Date: 2017/9/7
 * Time: 10:19
 */
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Seed the tags table
     */
    public function run()
    {
        Tag::truncate();

        factory(Tag::class, 5)->create();
    }
}