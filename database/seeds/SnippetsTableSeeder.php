<?php

use App\Tag;
use App\Like;
use App\User;
use App\Comment;
use App\Snippet;
use Illuminate\Database\Seeder;

class SnippetsTableSeeder extends Seeder
{
    public function run()
    {
        $tagNames = [
            'PHP',
            'Laravel',
            'JavaScript',
            'Vue',
            'React',
            'Python',
            'Django',
            'Java',
            'Spring',
            'C#',
            '.NET',
            'Ruby',
            'Rails',
            'Go',
            'Webdev',
            'Frontend',
            'Backend',
            'Database',
            'MySQL',
            'PostgreSQL',
            'DevOps',
            'Docker',
            'AWS'
        ];

        foreach ($tagNames as $tagName) {
            factory(Tag::class)->create(['name' => $tagName]);
        }

        factory(Snippet::class, 10)->create()->each(function ($snippet) {
            $tags = Tag::inRandomOrder()->limit(rand(1, 3))->pluck('id')->toArray();

            $snippet->tags()->attach($tags);
        });

        // Snippets with comment & likes

        $users = factory(User::class, 10)->create();

        factory(Snippet::class, 10)->create()->each(function ($snippet) use ($users) {
            $tags = Tag::inRandomOrder()->limit(rand(1, 3))->pluck('id')->toArray();
            $snippet->tags()->attach($tags);

            $commentCount = rand(0, 3);
            for ($i = 0; $i < $commentCount; $i++) {
                factory(Comment::class)->create([
                    'snippet_id' => $snippet->id,
                    'user_id' => $users->random()->id,
                ]);
            }

            $likeCount = rand(0, 5);
            $likedUsers = $users->random(min($likeCount, $users->count()));
            foreach ($likedUsers as $user) {
                factory(Like::class)->create([
                    'snippet_id' => $snippet->id,
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
