<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\UristBundle\Entity\Document;
use App\UristBundle\Utils\UristUtils;


class LoadDocumentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    { 
        // $doc_1 = new Document();
        // $doc_1->setName('Решение суда по наследству');
        // $doc_1->setPath('docs/'.UristUtils::getUrlFromTitle($doc_1->getName()).'.pdf');
        // $doc_1->addCategory($manager->merge($this->getReference('ap')));
        // $doc_2 = new Document();
        // $doc_2->setName('Решение суда по имуществу');
        // $doc_2->setPath('docs/'.UristUtils::getUrlFromTitle($doc_2->getName()).'.pdf');
        // $doc_2->addCategory($manager->merge($this->getReference('ap')));
        // $doc_2->addCategory($manager->merge($this->getReference('cp')));
        // $doc_3 = new Document();
        // $doc_3->setName('Решение по недобросовестной конкуренции');
        // $doc_3->setPath('docs/'.UristUtils::getUrlFromTitle($doc_3->getName()).'.pdf');
        // $doc_3->addCategory($manager->merge($this->getReference('cp')));
        // $manager->persist($doc_1);
        // $manager->persist($doc_2);
        // $manager->persist($doc_3);
        // $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
