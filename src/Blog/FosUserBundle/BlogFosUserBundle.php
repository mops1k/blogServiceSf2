<?php

namespace Blog\FosUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlogFosUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
