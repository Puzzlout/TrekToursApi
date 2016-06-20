<?php
namespace ApiBundle\Handler;

use FOS\RestBundle\Util\ExceptionWrapper;
use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;
use Symfony\Component\Debug\Exception\FlattenException;

class RestExceptionHandler implements ExceptionWrapperHandlerInterface {

    public function wrap($data)
    {

        $newException = array(
            'message' => $data['exception']->getMessage()
        );

        return $newException;
    }
}