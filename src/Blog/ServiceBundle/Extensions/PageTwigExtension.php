<?php
namespace Blog\ServiceBundle\Extensions;

use Blog\ServiceBundle\Extensions\PageExtension;

class PageTwigExtension extends \Twig_Extension
{
    protected $pages;

    function __construct(PageExtension $pages) {
        $this->pages = $pages;
    }

    public function getGlobals() {
        return array(
            'pages' => $this->pages->getPages()
        );
    }

    public function getName()
    {
        return 'page';
    }

}