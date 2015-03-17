<?php

namespace Blog\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function indexAction(Request $request)
    {
//        $page = $this->getDoctrine()
//            ->getManager()
//            ->getRepository('BlogServiceBundle:Page')
//            ->findOneBy([
//                'name' => 'index'
//            ])
//        ;
//
//        if(!isset($page))
//            throw new NotFoundHttpException();
        $result = null;

        if($request->getMethod() == 'POST') {
            //
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
}