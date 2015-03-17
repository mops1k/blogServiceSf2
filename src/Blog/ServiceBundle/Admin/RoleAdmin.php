<?php

namespace Blog\ServiceBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class RoleAdmin extends Admin
{
    public $supportsPreviewMode = true;
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Название', "by_reference" => false))
            ->add('title', 'text', array('label' => 'Имя'))
            ->add('description', 'textarea', array('label' => 'Описание'))
            ->add('children', 'sonata_type_model',
                ["by_reference" => false, "multiple" => true, "required" => false])
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('title')
            ->add('group', 'doctrine_orm_callback', array(
                'field_type' => 'checkbox',
                'callback' => function($queryBuilder, $alias, $field, $value) {

                    if (!$value['value']) {
                        return false;
                    }

                    /** @var $queryBuilder QueryBuilder */
                    $queryBuilder
                        ->select($alias.',c')
                        ->leftJoin(sprintf('%s.children',$alias),'c')
                        ->where('c.id is not null')
                    ;

                    return true;
                }
            ))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->addIdentifier('title')
            ->addIdentifier('children')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    protected function configureShowFields(ShowMapper $filter)
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('title')
            ->add('description')
            ->add('children')
        ;
    }


}