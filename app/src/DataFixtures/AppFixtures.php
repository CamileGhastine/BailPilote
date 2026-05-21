<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Post;
use App\Enum\ArticleType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setTitle('Droit immobilier');
        $manager->persist($category);

        $articles = [
            ['Comprendre son bail de location', ArticleType::Article],
            ['Comment réviser le loyer ?', ArticleType::Tutoriel],
            ['Les obligations du bailleur', ArticleType::Article],
            ['Rédiger un état des lieux', ArticleType::Tutoriel],
            ['La caution solidaire expliquée', ArticleType::Article],
            ['Gérer un impayé de loyer', ArticleType::Tutoriel],
            ['Le dépôt de garantie', ArticleType::Article],
            ['Donner congé à son locataire', ArticleType::Tutoriel],
            ['Assurance habitation du locataire', ArticleType::Article],
            ['Charges locatives : ce que vous devez savoir', ArticleType::Tutoriel],
            ['La loi ALUR en résumé', ArticleType::Article],
            ['Entretien et réparations : qui paie quoi ?', ArticleType::Tutoriel],
        ];

        foreach ($articles as $i => [$title, $type]) {
            $post = new Post();
            $post->setTitle($title)
                ->setSlug('post-' . ($i + 1))
                ->setDescription('Résumé : ' . strtolower($title) . '.')
                ->setType($type)
                ->setCategory($category)
                ->setCreatedAt(new \DateTimeImmutable("-$i days"))
                ->setUpdatedAt(new \DateTimeImmutable("-$i days"))
                ->setPublishedAt(new \DateTimeImmutable("-$i days"));

            $media = new Media();
            $media->setTitle('Image — ' . $title)
                ->setType('image')
                ->setPath(null)
                ->setUrl(null)
                ->setPost($post);

            $manager->persist($post);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
