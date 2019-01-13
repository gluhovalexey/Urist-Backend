<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\UristBundle\Entity\Service;


class LoadServiceData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    { 
        $serv_1 = new Service();
        $serv_1->setTitle('Устная юридическая консультация');
        $serv_1->setPrice('2000');
        $serv_1->addCategory($manager->merge($this->getReference('ap')));
        $serv_1->addCategory($manager->merge($this->getReference('cp')));
        $serv_2 = new Service();
        $serv_2->setTitle('Подготовка справок и заключений при сопровождении сделок');
        $serv_2->setPrice('5000');
        $serv_2->addCategory($manager->merge($this->getReference('cp')));
        $serv_3 = new Service();
        $serv_3->setTitle('Правовая экспертиза договоров (купли-продажи, поставки, услуги, подряда)');
        $serv_3->setPrice('5000');
        $serv_3->addCategory($manager->merge($this->getReference('ap')));
        $manager->persist($serv_1);
        $manager->persist($serv_2);
        $manager->persist($serv_3);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
