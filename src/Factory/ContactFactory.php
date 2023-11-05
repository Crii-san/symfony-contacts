<?php

namespace App\Factory;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Contact>
 *
 * @method        Contact|Proxy create(array|callable $attributes = [])
 * @method static Contact|Proxy createOne(array $attributes = [])
 * @method static Contact|Proxy find(object|array|mixed $criteria)
 * @method static Contact|Proxy findOrCreate(array $attributes)
 * @method static Contact|Proxy first(string $sortedField = 'id')
 * @method static Contact|Proxy last(string $sortedField = 'id')
 * @method static Contact|Proxy random(array $attributes = [])
 * @method static Contact|Proxy randomOrCreate(array $attributes = [])
 * @method static ContactRepository|RepositoryProxy repository()
 * @method static Contact[]|Proxy[] all()
 * @method static Contact[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Contact[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Contact[]|Proxy[] findBy(array $attributes)
 * @method static Contact[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Contact[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ContactFactory extends ModelFactory
{
    protected \Transliterator $transliterator;
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct($transliterator)
    {
        $this->transliterator = $transliterator;
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $lastname = self::faker()->lastName();
        $firstname = self::faker()->firstName();
        $lastname = $this->normalizeName($lastname);
        $firstname = $this->normalizeName($firstname);
        $domain = self::faker()->domainName();
        $email = $firstname.'.'.$lastname.'@'.$domain;

        return [
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Contact $contact): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Contact::class;
    }

    protected function normalizeName(string $name): string
    {
        $name = $this->transliterator->transliterate($name);
        $name = preg_replace('/[^a-zA-Z]/', '-', $name);
        return $name;
    }
}
