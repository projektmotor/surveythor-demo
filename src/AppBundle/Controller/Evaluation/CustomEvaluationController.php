<?php

namespace AppBundle\Controller\Evaluation;

use AppBundle\Entity\Result;

/**
 * EvaluationController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class CustomEvaluationController
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
