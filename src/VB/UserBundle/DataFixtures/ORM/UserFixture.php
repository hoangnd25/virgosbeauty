<?php
namespace VB\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VB\UserBundle\Entity\User;

class UserFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $hoangnd = new User();
        $hoangnd->setUsername('hoangnd');
        $hoangnd->setPlainPassword('123456');
        $hoangnd->setEmail('contact@hoangnd.me');
        $hoangnd->setEnabled(true);
        $hoangnd->setSuperAdmin(true);

        $manager->persist($hoangnd);
        $manager->flush();

        $this->addReference('hoangnd', $hoangnd);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}