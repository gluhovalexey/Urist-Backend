<?php

namespace App\ShopBundle\Service;


use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * class Validator
 */
class Validator
{
	private $validator;

	private $em;
    /**
     * summary
     */
    public function __construct(ValidatorInterface $validator, Registry $registry)
    {
        $this->validator = $validator;
        $this->$em = $registry;
    }

    public function validateRequest($data)
    {
    	$errors = $this->validator->validate($data);

    	$errorsResponse = array();

    	foreach ($errors as $error) {
    		$errorsResponse[] = [
    			"field"   => $error->getPropertyPath(),
    			"message" => $error->getMessage()
    		]
    	}

    	if (count($errors)){
    		$response = array(
    			"code"    => 1,
    			"message" => "validate errors",
    			"errors"  => $errorsResponse,
    			"result"  => null
    		);
    		return $response;
    	} else {
    		$response = array();
    		return $response;
    	}
    }
}
