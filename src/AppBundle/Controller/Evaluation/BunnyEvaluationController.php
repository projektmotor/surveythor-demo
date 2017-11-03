<?php

namespace AppBundle\Controller\Evaluation;

use AppBundle\Entity\Result;

class BunnyEvaluationController
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
