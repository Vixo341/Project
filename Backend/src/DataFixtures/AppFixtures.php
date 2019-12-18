<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AppFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
       $post = new Post();
       $post ->setTitle('First Post!');
       $post ->setPublished(new \DateTime('2019-12-18 12:00:00'));
       $post ->setContent('Post content');
       $post ->setAuthor('AM');
       $post ->setSlug('first-post');

        $manager->persist($post);


        $post = new Post();
        $post ->setTitle('Second Post!');
        $post ->setPublished(new \DateTime('2019-12-15 12:00:00'));
        $post ->setContent('Post content');
        $post ->setAuthor('AM-2');
        $post ->setSlug('second-post');

        $manager->persist($post);

        $manager->flush();
    }
}
