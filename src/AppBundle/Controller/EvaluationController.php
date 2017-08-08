<?php
namespace AppBundle\Controller;

use PM\SurveythorBundle\Entity\Result;

/**
 * EvaluationController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class EvaluationController
{
    public function evaluateResultAction(Result $result)
    {
        return array( 'result' => $result);
    }
}
