<?php
namespace Blog\ServiceBundle\Services;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;

class RoleHierarchy extends \Symfony\Component\Security\Core\Role\RoleHierarchy
{
    /** @var EntityManager em */
    private $em;
    private $hierarchy;

    /**
     * @param array $hierarchy
     */
    public function __construct(array $hierarchy, ManagerRegistry $em)
    {
        $this->em = $em->getManagerForClass("BlogServiceBundle:Role");
        $this->hierarchy = $hierarchy;
        parent::__construct($this->buildRolesTree());
    }

    /**
     * Here we build an array with roles. It looks like a two-levelled tree - just
     * like original Symfony roles are stored in security.yml
     * @return array
     */
    private function buildRolesTree()
    {
        $hierarchy = $this->hierarchy;
        $qb = $this->em->createQueryBuilder();
        $qb->select('r,p,c')
            ->from('BlogServiceBundle:Role','r')
            ->leftJoin('r.parents','p')
            ->leftJoin('r.children','c')
        ;
        $query = $qb->getQuery();

        $roles = $query->getResult(AbstractQuery::HYDRATE_OBJECT);

        foreach ($roles as $role) {
            /** @var $role \Blog\ServiceBundle\Entity\Role */
            if ($role->getParents()) {
                $parents = $role->getParents();
                foreach ($parents as $parent) {
                    if (!isset($hierarchy[$parent->getName()])) {
                        $hierarchy[$parent->getName()] = array();
                    }
                    $hierarchy[$parent->getName()][] = $role->getName();
                }
            } else {
                if (!isset($hierarchy[$role->getName()])) {
                    $hierarchy[$role->getName()] = array();
                }
            }
        }
        return $hierarchy;
    }
}