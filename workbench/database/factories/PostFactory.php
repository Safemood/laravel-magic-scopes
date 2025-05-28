<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // Boolean Fields
            'is_published' => $this->faker->boolean,
            'has_featured_image' => $this->faker->boolean,
            'is_sticky' => $this->faker->boolean,
            'is_reviewed' => $this->faker->boolean,

            // Date Fields
            'published_at' => $this->faker->optional()->dateTime,
            'reviewed_at' => $this->faker->optional()->dateTime,

            // Enum Fields
            'status' => $this->faker->randomElement(['draft', 'pending', 'published']),
            'post_type' => $this->faker->randomElement(['article', 'tutorial', 'news']),
            'visibility' => $this->faker->randomElement(['public', 'private', 'restricted']),

            // Foreign Key Fields
            'author_id' => User::factory(),
            'category_id' => Category::factory(),
            'editor_id' => User::factory(),
            'parent_post_id' => null,

            // JSON Fields
            'metadata' => $this->faker->optional()->randomElements(['key' => $this->faker->word]),
            'settings' => $this->faker->optional()->randomElements(['setting' => $this->faker->word]),
            'tags' => $this->faker->optional()->words(5),

            // Number Fields
            'views' => $this->faker->numberBetween(0, 10000),
            'likes' => $this->faker->numberBetween(0, 5000),
            'shares' => $this->faker->numberBetween(0, 2000),
            'comments_count' => $this->faker->numberBetween(0, 1000),
            'priority' => $this->faker->optional()->randomFloat(2, 0, 5),

            // String Fields
            'title' => $this->faker->sentence,
            'slug' => Str::slug($this->faker->unique()->sentence),
            'content' => $this->faker->paragraphs(5, true),
            'excerpt' => $this->faker->optional()->text(200),
            'featured_image' => $this->faker->optional()->imageUrl,
        ];
    }
}
