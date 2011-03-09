<?php

namespace Equinoxe\DataStoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DataStoreBundle:Default:index.html.twig');
    }
}
