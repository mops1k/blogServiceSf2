<?php

namespace Blog\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlogServiceBundle:Main:Index.html.twig');
    }

    public function pageAction($name){
        $page = $this->getDoctrine()
            ->getManager()
            ->getRepository('BlogServiceBundle:Page')
            ->findOneBy([
                'name' => $name
            ])
        ;

        return $this->render('BlogServiceBundle:Main:Page.html.twig',[
            'title' => $page->getTitle(),
            'page'  => $page
        ]);
    }
}