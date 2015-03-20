<?php
namespace Blog\ServiceBundle\Extensions;

use Doctrine\ORM\EntityManager;
use Blog\ServiceBundle\Entity\Page;

class PageExtension
{
    /** @var EntityManager $em */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getPages()
    {
        $pages = $this->em
            ->getRepository('BlogServiceBundle:Page')
            ->findAll()
        ;

        return $pages;
    }
}
