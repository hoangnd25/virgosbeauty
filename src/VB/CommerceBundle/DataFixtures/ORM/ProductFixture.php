<?php
namespace VB\CommerceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VB\CommerceBundle\Entity\Product;
use VB\CommerceBundle\Entity\ProductCategory;
use VB\CommerceBundle\Entity\ProductProperty;
use VB\CommerceBundle\Entity\PropertyValue;
use VB\UserBundle\Entity\User;

class ProductFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $root =  new ProductCategory('root');
        $beauty =  new ProductCategory('Làm đẹp',$root);
        $hairCare =  new ProductCategory('Chăm sóc tóc',$beauty);
        $skinCare =  new ProductCategory('Chăm sóc da',$beauty);
        $cosmetic =  new ProductCategory('Mỹ phẩm',$beauty);
        $lipstick =  new ProductCategory('Son môi',$cosmetic);
        $heathCare =  new ProductCategory('Chăm sóc sức khoẻ',$root);
        $healthyFood =  new ProductCategory('Thực phẩm chức năng',$heathCare);
        $kidHealthCare =  new ProductCategory('Dành cho trê nhỏ',$heathCare);

        $sheepPlacenta =  new Product();
        $sheepPlacenta->setName('Sheep placenta');
        $sheepPlacenta->setTagLine('Chu thich');
        $skinCare->getProducts()->add($sheepPlacenta);
        $sheepPlacenta->setPrice('300000');
        $sheepPlacenta->setOldPrice('350000');
        $manager->persist($sheepPlacenta);

        $revlonLipstick =  new Product();
        $revlonLipstick->setName('Revlon lipstick');
        $revlonLipstick->setTagLine('Chu thich');
        $lipstick->getProducts()->add($revlonLipstick);
        $revlonLipstick->setPrice('200000');
        $property = new ProductProperty('color','Mau',$revlonLipstick);
        $color1 = new PropertyValue('color 1',$property);
        $color2 = new PropertyValue('color 2',$property);
        $manager->persist($revlonLipstick);

        $manager->persist($root);
        $manager->flush();

//        $this->addReference('hoangnd', $hoangnd);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}