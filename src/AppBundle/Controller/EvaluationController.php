<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Result;

/**
 * EvaluationController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class EvaluationController
{
    /**
     * @param Result $result
     *
     * @return array
     */
    public function evaluateResultAction(Result $result)
    {
        return ['result' => $result];
    }
}
