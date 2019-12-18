<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadPosts($manager);
        $this->loadComments($manager);
    }

    public function loadPosts(ObjectManager $manager)
    {

        $user = $this->getReference('user_admin');

        for ($i = 1; $i < 100; $i++)
        {

            $post = new Post();
            $post ->setTitle($this->faker->realText(30));
            $post ->setPublished($this->faker->dateTimeThisYear);
            $post ->setContent($this->faker->realText());
            $post ->setAuthor($user);
            $post ->setSlug($this->faker->slug);

            $this->setReference("post_$i", $post);

            $manager->persist($post);
        }


        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i = 1; $i < 100; $i++)
        {
            for ($j = 0; $j < rand(1, 10); $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($this->getReference('user_admin'));
                $comment->setPost($this->getReference("post_$i"));
                $manager->persist($comment);
            }
        }
        $manager->flush();

    }

    public function loadUsers(ObjectManager $manager)
    {
            $user = new User();
            $user->setUsername('admin');
            $user->setEmail('admin@admin.com');
            $user->setName('Artur');

            $user->setPassword($this->passwordEncoder->encodePassword($user, 'pass'));


            $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();

    }
}
