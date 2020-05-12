<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Post;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        for ($c = 0; $c < 4; $c++) {
            $category = new Category();
            $category->setTitle($faker->catchPhrase);

            $manager->persist($category);

            for ($i = 0; $i < 20; $i++) {
                $post = new Post();
                $post->setTitle($faker->catchPhrase)
                    ->setContent($faker->paragraphs(5, true))
                    ->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'))
                    ->setCategory($category)
                    ->setImage($faker->imageUrl(400, 400, true));

                $manager->persist($post);
            }
        }






        $manager->flush();
    }
}
