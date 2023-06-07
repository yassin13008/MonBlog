<?php

namespace App\Factory;

use App\Entity\Post;

use Zenstruck\Foundry\Proxy;
use App\Repository\PostRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\RepositoryProxy;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @extends ModelFactory<Post>
 *
 * @method        Post|Proxy create(array|callable $attributes = [])
 * @method static Post|Proxy createOne(array $attributes = [])
 * @method static Post|Proxy find(object|array|mixed $criteria)
 * @method static Post|Proxy findOrCreate(array $attributes)
 * @method static Post|Proxy first(string $sortedField = 'id')
 * @method static Post|Proxy last(string $sortedField = 'id')
 * @method static Post|Proxy random(array $attributes = [])
 * @method static Post|Proxy randomOrCreate(array $attributes = [])
 * @method static PostRepository|RepositoryProxy repository()
 * @method static Post[]|Proxy[] all()
 * @method static Post[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Post[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Post[]|Proxy[] findBy(array $attributes)
 * @method static Post[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Post[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class PostFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        parent::__construct();

        $this->slugger = $slugger;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'author' => self::faker()->text(255),
            'content' => self::faker()->text(),
            'image' => 'https://picsum.photos/seed/post-' . rand(0,500) . '/750/300',
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-3 years', 'now', 'Europe/Paris')),
            'title' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
        ->afterInstantiate(function(Post $post) {
            // On récupère le titre de l'article
            $title = $post->getTitle();

            // On sluggifie ce titre avec le slugger
            $slug = $this->slugger->slug($title);

            // On enregistre ce slug dans le champ slug
            $post->setSlug($slug);
        });
    }

    protected static function getClass(): string
    {
        return Post::class;
    }
}
