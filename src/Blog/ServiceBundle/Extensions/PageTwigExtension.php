<?php
namespace Blog\ServiceBundle\Extensions;


class PageTwigExtension extends \Twig_Extension
{
    protected $pages;

    public function __construct(PageExtension $pages)
    {
        $this->pages = $pages;
    }

    public function getGlobals()
    {
        return array(
            'pages' => $this->pages->getPages(),
        );
    }

    public function getName()
    {
        return 'page';
    }
}
