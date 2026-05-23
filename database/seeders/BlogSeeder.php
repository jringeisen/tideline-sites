<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->first() ?? User::factory()->create([
            'name' => 'Tideline Admin',
            'email' => 'admin@example.com',
            'bio' => 'Lead writer for Tideline.',
        ]);

        $categories = Category::factory()->count(3)->create();
        $tags = Tag::factory()->count(8)->create();

        Post::factory()
            ->count(12)
            ->published()
            ->state(fn () => [
                'author_id' => $author->id,
                'category_id' => $categories->random()->id,
            ])
            ->create()
            ->each(fn (Post $post) => $post->tags()->sync($tags->random(rand(1, 3))->pluck('id')));

        Post::factory()
            ->count(2)
            ->draft()
            ->state(fn () => [
                'author_id' => $author->id,
                'category_id' => $categories->random()->id,
            ])
            ->create();

        Post::factory()
            ->count(1)
            ->scheduled(now()->addDay())
            ->state(fn () => [
                'author_id' => $author->id,
                'category_id' => $categories->random()->id,
            ])
            ->create();
    }
}
