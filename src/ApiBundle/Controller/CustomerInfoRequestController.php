<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Request\ParamFetcher;

class CustomerInfoRequestController extends FOSRestController
{
    /**
     * @param ParamFetcher $paramFetcher
     * @return View
     *
     * @Rest\Get("/customerinforequests.{_format}")
     * @Rest\QueryParam(name="limit", requirements="\d+", default=10, description="Number of results.")
     * @Rest\QueryParam(name="offset", requirements="\d+", default=0, description="Result offset.")
     * @Rest\QueryParam(
     *     name="from",
     *     requirements="^(19|20)\d\d[-/.](0[1-9]|1[012])[-/.](0[1-9]|[12][0-9]|3[01])$",
     *     description="From date (yyyy-mm-dd format).")
     * @Rest\QueryParam(
     *     name="to",
     *     requirements="^(19|20)\d\d[-/.](0[1-9]|1[012])[-/.](0[1-9]|[12][0-9]|3[01])$",
     *     description="To date (yyyy-mm-dd format).")
     * @ApiDoc(
     *  resource = true,
     *  description = "List of customer info requests",
     *  statusCodes={
     *         200="Returned on success",
     *         403="Returned when request isn't valid"
     *  }
     * )
     */
    public function getCustomerInfoRequestAction(ParamFetcher $paramFetcher)
    {
        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()->setGroups('list'));

        $repository = $this->getDoctrine()->getManager()->getRepository('ApiBundle:CustomerInfoRequest');
        $customerInfoRequests = null;
        $customerInfoRequests = $repository->findAllWithFilters(
            $paramFetcher->get('offset'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('from'),
            $paramFetcher->get('to'));
        if(!is_null($customerInfoRequests))
        {
            $view->setData($customerInfoRequests);
            $view->setStatusCode(Response::HTTP_OK);
        } else {
            $view->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $this->handleView($view);
    }
}
