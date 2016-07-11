<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Request\ParamFetcher;
use ApiBundle\Entity\CustomerInfoRequest;

class CustomerInfoRequestController extends FOSRestController
{
    /**
     * @param ParamFetcher $paramFetcher
     * @return View
     *
     * @Rest\Get("/customerinforequests.{_format}", name="api_get_customerinforequests")
     * @Rest\QueryParam(name = "limit", requirements = "\d+", default = 10, description = "Number of results.")
     * @Rest\QueryParam(name = "offset", requirements = "\d+", default = 0, description = "Result offset.")
     * @Rest\QueryParam(
     *     name = "from",
     *     strict = true,
     *     nullable = true,
     *     requirements = "^(19|20)\d\d[-/.](0[1-9]|1[012])[-/.](0[1-9]|[12][0-9]|3[01])$",
     *     description = "From date (yyyy-mm-dd format)."),
     *     allowBlank = false
     * @Rest\QueryParam(
     *     name = "to",
     *     strict = true,
     *     nullable = true,
     *     requirements = "^(19|20)\d\d[-/.](0[1-9]|1[012])[-/.](0[1-9]|[12][0-9]|3[01])$",
     *     description = "To date (yyyy-mm-dd format)."),
     *     allowBlank = false
     * @ApiDoc(
     *  section = "Customer Info Requests",
     *  resource = true,
     *  headers = {
     *      { "name" = "Authorization", "required" = true, "description" = "Authorization: Bearer JWT" }
     *  },
     *  requirements = {
     *      { "name" = "_format", "dataType" = "string", "requirement" = "(json|xml)", "default" = "json" }
     *  },
     *  description = "List of customer info requests",
     *  output = { "class" = "ApiBundle\Entity\CustomerInfoRequest", "groups" = {"list"} },
     *  statusCodes={
     *         200="Returned on success",
     *         403="Returned when request isn't valid"
     *  }
     * )
     */
    public function getCustomerInfoRequestsAction(ParamFetcher $paramFetcher)
    {
        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()->setGroups(['list']));
        $repository = $this->getDoctrine()->getManager()->getRepository('ApiBundle:CustomerInfoRequest');
        $customerInfoRequests = null;
        $customerInfoRequests = $repository->findAllWithFilters(
            $paramFetcher->get('offset'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('from'),
            $paramFetcher->get('to'));
        if(!is_null($customerInfoRequests['items'])) {
            $view->setData($customerInfoRequests['items'])->setStatusCode(Response::HTTP_OK);
            $view->setHeader('X-Total-Count', $customerInfoRequests['totalCount']);
            return $this->handleView($view);
        }

        return $view->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param ParamFetcher $paramFetcher
     * @return View
     *
     * @Rest\Get("/customerinforequests/{id}.{_format}", name="api_get_customerinforequest" ,
     *     requirements = { "id" = "\d+"})
     * @ApiDoc(
     *  section = "Customer Info Requests",
     *  resource = true,
     *  headers = {
     *      { "name" = "Authorization", "required" = true, "description" = "Authorization: Bearer JWT" }
     *  },
     *  requirements = {
     *     { "name" = "id", "dataType" = "integer", "requirement" = "\d+", "description" = "CustomerInfoRequest ID" },
     *     { "name" = "_format", "dataType" = "string", "requirement" = "(json|xml)", "default" = "json" }
     *     },
     *  description = "Get CustomerInfoRequest by ID",
     *  output = { "class" = "ApiBundle\Entity\CustomerInfoRequest", "groups" = {"details"} },
     *  statusCodes={
     *         200="Returned on success",
     *         403="Returned when request isn't valid"
     *  }
     * )
     */
    public function getCustomerInfoRequestAction($id)
    {
        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()->setGroups(['details']));

        $repository = $this->getDoctrine()->getManager()->getRepository('ApiBundle:CustomerInfoRequest');
        $customerInfoRequest = null;
        $customerInfoRequest = $repository->find($id);
        if(!is_null($customerInfoRequest)) {
            $view->setData($customerInfoRequest)->setStatusCode(Response::HTTP_OK);
        } else {
            $view->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->handleView($view);
    }

    /**
     * @param ParamFetcher $paramFetcher
     * @return View
     *
     * @Rest\Post("/customerinforequests.{_format}", name="api_post_customerinforequests")
     * @Rest\RequestParam(
     *      name = "email",
     *      nullable = false,
     *      requirements = {
     *          "rule" = "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}",
     *          "error_message" = "Email isn't valid." },
     *      description = "Email address."
     * )
     * @Rest\RequestParam(
     *     name = "first_name",
     *     nullable = false,
     *     requirements = {
     *          "rule" = "[\p{L}]+",
     *          "error_message" = "First name must contain only alphabetic characters." },
     *     description = "First name."
     * )
     * @Rest\RequestParam(
     *     name = "last_name",
     *     nullable = false,
     *     requirements = {
     *          "rule" = "[\p{L}]+",
     *          "error_message" = "Last name must contain only alphabetic characters." },
     *     description = "Last name."
     * )
     * @Rest\RequestParam(
     *     name = "phone_number",
     *     nullable = true,
     *     requirements = {
     *          "rule" = "\+\d+",
     *          "error_message" = "Phone number in international format (+XXXYYYYYYYYYY)." },
     *     description = "Phone number in international format (+XXXYYYYYYYYYY)."
     * )
     * @Rest\RequestParam(
     *     name = "message",
     *     nullable = false,
     *     requirements = {
     *          "rule" = "^(?!.*<[^>]+>).*",
     *          "error_message" = "Message can't contain html tags" },
     *     description = "Message."
     * )
     * @Rest\RequestParam(
     *     name = "has_sent_copy_to_client",
     *     nullable = false,
     *     default = 0,
     *     requirements = {
     *          "rule" = "(0|1)",
     *          "error_message" = "Can be only 0 or 1" },
     *     description = "Send copy of email to client."
     * )
     *
     * @ApiDoc(
     *  section = "Customer Info Requests",
     *  resource = true,
     *  description = "Create new CustomerInfoRequest",
     *  requirements = {
     *      { "name" = "_format", "dataType" = "string", "requirement" = "(json|xml)", "default" = "json" }
     *  },
     *  output = { "class" = "ApiBundle\Entity\CustomerInfoRequest", "groups" = {"details"} },
     *  statusCodes={
     *     200 = "Returned on success",
     *     400 = "Returned on invalid parameter",
     *     403 = "Returned when request isn't valid"
     *  }
     * )
     */
    public function postCustomerInfoRequestAction(ParamFetcher $paramFetcher)
    {
        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()->setGroups(['details'])->setSerializeNull(true));

        $customerInfoRequest = new CustomerInfoRequest();
        $customerInfoRequest->setEmail($paramFetcher->get('email'));
        $customerInfoRequest->setFirstName($paramFetcher->get('first_name'));
        $customerInfoRequest->setLastName($paramFetcher->get('last_name'));
        $customerInfoRequest->setMessage($paramFetcher->get('message'));
        $customerInfoRequest->setPhoneNumber($paramFetcher->get('phone_number'));
        $customerInfoRequest->setHasSentCopyToClient($paramFetcher->get('has_sent_copy_to_client'));
        $customerInfoRequest->setStatus(CustomerInfoRequest::STATUS_TBP);
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customerInfoRequest);
            $entityManager->flush();
            $view->setStatusCode(Response::HTTP_CREATED)->setData($customerInfoRequest);
            $view->setHeader('Location', $this->get('router')->generate('api_get_customerinforequest',
                ['id' => $customerInfoRequest->getId()]));
            $message = \Swift_Message::newInstance()
                ->setSubject('New Customer Info Request')
                ->setFrom($this->container->getParameter('mail_admin_address'))
                ->setTo($this->container->getParameter('mail_admin_address'))
                ->setBody(
                    $this->renderView(
                        'ApiBundle:emails:customerinforequest.html.twig',
                        [
                            'first_name' => $paramFetcher->get('first_name'),
                            'last_name' => $paramFetcher->get('last_name'),
                            'message' => $paramFetcher->get('message'),
                            'phone_number' => $paramFetcher->get('phone_number'),
                            'email' => $paramFetcher->get('email')
                        ]
                    ),
                    'text/html'
                )->addPart(
                    $this->renderView(
                        'ApiBundle:emails:customerinforequest.txt.twig',
                        [
                            'first_name' => $paramFetcher->get('first_name'),
                            'last_name' => $paramFetcher->get('last_name'),
                            'message' => $paramFetcher->get('message'),
                            'phone_number' => $paramFetcher->get('phone_number'),
                            'email' => $paramFetcher->get('email')
                        ]
                    ),
                    'text/plain'
                )
            ;
            if($paramFetcher->get('has_sent_copy_to_client') == 1) {
                $message->addCc($paramFetcher->get('email'));
            }
            if($this->container->getParameter('mail_admin_address')) {
                $this->get('mailer')->send($message);
            }


        } catch (\Exception $e) {
            $view->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $this->handleView($view);
    }


    /**
     * @var ParamFetcher $paramFetcher
     * @return View
     *
     * @Rest\Patch("/customerinforequests/{id}/status.{_format}", name="api_patch_customerinforequests")
     * @Rest\RequestParam(
     *     name = "status",
     *     nullable  = false,
     *     requirements = { "rule" = "(TBP|RTC|RQC)", "error_message" = "Status can be TBP, RTC or RQC." },
     *     description = "Status to replace" )
     * @ApiDoc(
     *  section = "Customer Info Requests",
     *  description = "Update status of CustomerInfoRequest",
     *  headers = {
     *      { "name" = "Authorization", "required" = true, "description" = "Authorization: Bearer JWT" }
     *  },
     *  requirements = {
     *      { "name" = "id", "dataType" = "integer", "requirement" = "\d+" },
     *      { "name" = "_format", "dataType" = "string", "requirement" = "(json|xml)", "default" = "json" }
     *  },
     *  output = { "class" = "ApiBundle\Entity\CustomerInfoRequest", "groups" = {"details"} },
     *  statusCodes = {
     *     200 = "Returned on success",
     *     400 = "Returned on invalid parameter",
     *     403 = "Returned when request isn't valid",
     *     404 = "Returned when CustomerRequestInfo isn't found"
     *  }
     * )
     */
    public function patchCustomerInfoRequest(ParamFetcher $paramFetcher, $id)
    {
        $view = $this->view();
        $view->setSerializationContext(SerializationContext::create()->setGroups(['details']));

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ApiBundle:CustomerInfoRequest');

        $customerInfoRequest = $repository->find($id);
        if(is_null($customerInfoRequest)) {
            $view->setStatusCode(Response::HTTP_NOT_FOUND);
        } else {
            try {
                $customerInfoRequest->setStatus($paramFetcher->get('status'));
                $em->persist($customerInfoRequest);
                $em->flush();
                $view->setStatusCode(Response::HTTP_OK)->setData($customerInfoRequest);
            } catch (\Exception $e) {
                $view->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->handleView($view);
    }
}
