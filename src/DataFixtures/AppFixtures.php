<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;


    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word);
            $ingredient->setPrice(mt_rand(0, 100));

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        for ($j = 1; $j <= 25; $j++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word);
            $recipe->setTime(mt_rand(1, 1440));
            $recipe->setPeople(mt_rand(1, 50));
            $recipe->setDifficulty(mt_rand(1, 5));
            $recipe->setDescription($this->faker->text(300));
            $recipe->setPrice(mt_rand(0, 1000));
            $recipe->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);
            for ($k = 1; $k <= mt_rand(3, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            $manager->persist($recipe);
        }
        for ($l = 1; $l <= 25; $l++) {
            $user = new User();
            $user->setFullName($this->faker->word);
            $user->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null);
            $user->setEmail($this->faker->email);
            $user->setPlainPassword('password');
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);

            $manager->flush();
        }
    }
}
