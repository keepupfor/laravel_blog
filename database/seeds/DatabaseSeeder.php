<?php

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(PostTableSeeder::class);

        Model::reguard();
    }
}
class PostTableSeeder extends Seeder
{
    public function run()
    {
        Post::truncate();
        factory(Post::class, 20)->create();
    }
}
