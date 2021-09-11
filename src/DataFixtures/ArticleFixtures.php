<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\entity\Article;
use App\entity\Category;
use App\entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker=\Faker\Factory::create('fr_FR');
        //create 3 fake Categories

        for($i=1; $i<=3; $i++){
            $category=new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
        $manager->persist($category);  
        
        //create 4 to 6  fake articles

        for($j=1; $j<=mt_rand(4,6); $j++){
            $article=new article();
           // $Content = '<p>' . join($faker->paragraphs(5), '</p> <p>').'</p>';
            $article->setTitle($faker->sentence())
                    ->setContent($faker->paragraph())
                    ->setImage($faker->imageUrl(350,150)) 
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);
             $manager->persist($article);

            //create 4 to 10 fake comments 

            for($k=1; $k<= mt_rand(4,10); $k++){
                $comment=new comment();
                //$Content = '<p>' . join($faker->paragraphs(2), '</p> <p>').'</p>';
                $now=new \datetime();
                $interval=$now->diff($article->getCreatedAt());
                $days=$interval->days;
                $min='-'. $days. 'days';

                $comment->setAuthor($faker->name())
                        ->setContent($faker->paragraph())
                        ->setCreatedAt($faker->dateTimeBetween($min))
                        ->setArticle($article);

                $manager->persist($comment) ;                           
            }
        }
        
    
        }
    
       

        $manager->flush();
    }
}
