<?php

namespace Blog\ServiceBundle\Controller;

use Doctrine\ORM\AbstractQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Blog\ServiceBundle\Entity\Blog;
use Blog\ServiceBundle\Form\BlogType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Blog controller.
 *
 */
class BlogController extends Controller
{

    /**
     * Lists all Blog entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()
            ->getEntityManager()
        ;
        $qb = $em->createQueryBuilder();

        $query = $qb->select('b,u')
            ->from('BlogServiceBundle:Blog','b')
            ->leftJoin('b.user','u')
            ->where('b.user = :user')
            ->setParameter('user',$this->getUser())
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('BlogServiceBundle:Blog:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new Blog entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Blog();
        $entity->setUser($this->getUser());
        $entity->setDate(new \DateTime());
        $entity->setPhotos('');

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('profile_blog'));
        }

        return $this->render('BlogServiceBundle:Blog:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Blog entity.
     *
     * @param Blog $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Blog $entity)
    {
        $form = $this->createForm(new BlogType(), $entity, array(
            'action' => $this->generateUrl('profile_blog_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Blog entity.
     *
     */
    public function newAction()
    {
        $entity = new Blog();
        $form   = $this->createCreateForm($entity);

        return $this->render('BlogServiceBundle:Blog:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Blog entity.
     *
     */
    public function showAction($name, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()
            ->getEntityManager()
        ;
        $qb = $em->createQueryBuilder();

        $qb->select('b,u')
            ->from('BlogServiceBundle:Blog','b')
            ->leftJoin('b.user','u')
            ->where('b.id = :id')
            ->andWhere('u.username = :username')
            ->setParameters([
                ':id'       => $id,
                ':username' => $name
            ])
        ;

        $entity = $qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);

        $owner = $entity->getUser() == $this->getUser() ? true : false;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BlogServiceBundle:Blog:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'owner'       => $owner
        ));
    }

    /**
     * Displays a form to edit an existing Blog entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BlogServiceBundle:Blog')->find($id);

        $canEdit = false;

        if($entity->getUser() == $this->getUser()) $canEdit = true;
        if($this->isGranted('ROLE_SUPER_ADMIN')) $canEdit = true;
        if(!$canEdit)
            throw new AccessDeniedHttpException('У вас нет прав на редактирование этого блога');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BlogServiceBundle:Blog:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Blog entity.
    *
    * @param Blog $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Blog $entity)
    {
        $form = $this->createForm(new BlogType(), $entity, array(
            'action' => $this->generateUrl('profile_blog_update', [
                'id' => $entity->getId()
            ]),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Blog entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BlogServiceBundle:Blog')->find($id);

        $canEdit = false;

        if($entity->getUser() == $this->getUser()) $canEdit = true;
        if($this->isGranted('ROLE_SUPER_ADMIN')) $canEdit = true;
        if(!$canEdit)
            throw new AccessDeniedHttpException('У вас нет прав на редактирование этого блога');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('profile_blog_edit', array('id' => $id)));
        }

        return $this->render('BlogServiceBundle:Blog:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Blog entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BlogServiceBundle:Blog')->find($id);

            $canEdit = false;

            if($entity->getUser() == $this->getUser()) $canEdit = true;
            if($this->isGranted('ROLE_SUPER_ADMIN')) $canEdit = true;
            if(!$canEdit)
                throw new AccessDeniedHttpException('У вас нет прав на редактирование этого блога');

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Blog entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('profile_blog'));
    }

    /**
     * Creates a form to delete a Blog entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('profile_blog_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
