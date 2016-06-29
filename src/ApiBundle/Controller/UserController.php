<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class UserController extends FOSRestController
{
    /**
     * @return View
     * @Rest\Get("/me.{_format}", name="api_get_me")
     * @ApiDoc(
     *  section = "Users",
     *  resource = true,
     *  requirements = {
     *      { "name" = "_format", "dataType" = "string", "requirement" = "(json|xml)", "default" = "json" },
     *  },
     *  description = "User profile",
     *  output = { "class" = "ApiBundle\Entity\User", "parsers" = { "Nelmio\ApiDocBundle\Parser\JmsMetadataParser" } },
     *  statusCodes={
     *         200="Returned on success",
     *         403="Returned when request isn't valid"
     *  }
     * )
     */
    public function getMeAction()
    {
        $view = View::create();

        $view->setStatusCode(RESPONSE::HTTP_OK);
        $view->setData($this->getUser());
        return $view;
    }
}
