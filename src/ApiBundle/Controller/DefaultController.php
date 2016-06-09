<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FOSRestController
{
    /**
     * @return View
     * @Rest\Get("/.{_format}")
     */
    public function getIndexAction()
    {
        $view = View::create();

        $view->setData('Welcome');
        $view->setStatusCode(RESPONSE::HTTP_OK);

        return $view;
    }
}
