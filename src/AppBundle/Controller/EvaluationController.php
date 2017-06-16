<?php
namespace AppBundle\Controller;

use PM\SurveythorBundle\Entity\Result;

/**
 * EvaluationController
 * @author Rombo Kraft <kraft@rastalavista.com>
 */
class EvaluationController
{
    public function evaluateResultAction(Result $result)
    {
        return array( 'result' => $result);
    }
}
