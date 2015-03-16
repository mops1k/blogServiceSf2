<?php

namespace Blog\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function indexAction()
    {
        $page = $this->getDoctrine()
            ->getManager()
            ->getRepository('BlogServiceBundle:Page')
            ->findOneBy([
                'name' => 'index'
            ])
        ;

        if(!isset($page))
            throw new NotFoundHttpException();

        return $this->render('BlogServiceBundle:Main:Index.html.twig',[
            'page' => $page
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
}