<?php

namespace Blog\ServiceBundle\Controller;

use Doctrine\ORM\AbstractQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
        $result = null;

        if($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()
                ->getEntityManager();
            $qb = $em->createQueryBuilder();

            $query = $qb->select('u,b')
                ->from('BlogServiceBundle:User','u')
                ->leftJoin('u.posts','b')
                ->where('u.username LIKE :search')
                ->andWhere('b.id is not null')
                ->setParameter(':search','%'.$request->get('search').'%')
            ;

            $paginator  = $this->get('knp_paginator');
            $result = $paginator->paginate(
                $query,
                $request->query->get('page', 1)/*page number*/,
                10/*limit per page*/
            );
        }

        return $this->render('BlogServiceBundle:Main:Index.html.twig',[
            'result' => $result,
            'search' => $request->get('search')
        ]);
    }

    public function pageAction($name){

        $page = $this->getDoctrine()
            ->getManager()
            ->getRepository('BlogServiceBundle:Page')
            ->findOneBy([
                'name' => $name
            ])
        ;

        if(!isset($page))
            throw new NotFoundHttpException();

        return $this->render('BlogServiceBundle:Main:Page.html.twig',[
            'title' => $page->getTitle(),
            'page'  => $page
        ]);
    }

    public function profileAction($name) {
        $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('BlogServiceBundle:User')
            ->findOneBy([
                'username' => $name
            ])
        ;

        if(!$user)
            throw new NotFoundHttpException('User not found');

        return $this->render('BlogServiceBundle:Main:Profile.html.twig',[
            'user' => $user
        ]);
    }
}