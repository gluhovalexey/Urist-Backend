<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\UristBundle\Entity\Category;


class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    { 
        $categories = ['Арбитражное право', 'Гражданское право', 'Земельное право', 'Интеллектуальное право', 'Международное частное право', 'Семейное право', 'Трудовое право', 'Уголовное право', 'Экологическое право'];
        for ($i=0; $i < count($categories); $i++){
            $cat = new Category();
            $cat->setTitle($categories[$i]);
            $manager->persist($cat);
        }
        
        $cp = new Category();
        $cp->setTitle('Коммерческое право');
        $ap = new Category();
        $ap->setTitle('Административное право');
        // $tp = new Category();
        // $tp->setTitle('Тестовое право');
        // $tp->setParent($ap);
        $manager->persist($cp);
        $manager->persist($ap);
        // $manager->persist($tp);
        $manager->flush();

        $this->addReference('cp', $cp);
        $this->addReference('ap', $ap);
    }

    public function getOrder()
    {
        return 1;
    }
}
