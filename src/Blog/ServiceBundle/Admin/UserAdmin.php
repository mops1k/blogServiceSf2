<?php

namespace Blog\ServiceBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends Admin
{
    public $supportsPreviewMode = true;

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('realname')
            ->add('username', 'text', array('label' => 'Логин','help'=>'Необходимо для авторизации на ресурсах'))
            ->add('email', 'email', array('label' => 'Почта'))
            ->add('enabled','checkbox')
            ->add('blogName')
            ->add('locked')
            ->add(
                'userRoles',
                'sonata_type_model_autocomplete',
                [
                    "property" => "title",
                    "by_reference" => false,
                    "multiple" => true,
                    "required" => false
                ]
            )
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('enabled')
            ->add('locked')
            ->add('userRoles')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('username')
            ->add('posts')
            ->add('enabled')
            ->add('locked')
            ->add('userRoles')
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
            ->add('realname')
            ->add('username', 'text', array('label' => 'Логин'))
            ->add('password', 'text', array('label' => 'Пароль'))
            ->add('email', 'email', array('label' => 'Почта'))
            ->add('userRoles')
        ;
    }
}