<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      // Instanciation de PostFactory en appelant la méthode statique new()
      PostFactory::new()
    
            // Création de 10 articles
            ->createMany(10);
    
      // Enregistrement dans la base de données
      $manager->flush();
    }
}
