<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\UristBundle\Entity\Role;
use App\UristBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsersRolesData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $role = new Role();
        $role->setName('ROLE_ADMIN');
        $role->setDescription('Учётная запись администратора');
        
        $manager->persist($role);

        $role2 = new Role();
        $role2->setName('ROLE_SUPER_ADMIN');
        $role2->setDescription('Учётная запись cуперадминистратора');
        $manager->persist($role2);

        $role3 = new Role();
        $role3->setName('ROLE_EMPLOYEE');
        $role3->setDescription('Сотрудник');
        $manager->persist($role3);

        //Добавление пользователя админа
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@shop.my');

        $adminPass = $encoder->encodePassword($user, 'jgnbrf');
        $user->setPassword($adminPass);

        $user->getUserRoles()->add($role);
        $user->getUserRoles()->add($role2);
        
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername('staff');
        $user2->setEmail('staff@shop.my');

        $pass = $encoder->encodePassword($user2, '1234');
        $user2->setPassword($pass);

        $user2->getUserRoles()->add($role3);
        
        $manager->persist($user2);

        $manager->flush();
    }
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }
}
