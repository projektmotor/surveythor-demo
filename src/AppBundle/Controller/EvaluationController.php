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
        /*
        foreach ($result->getResultAnswers() as $v) {
            dump($v);
            foreach ($v->getChildAnswers() as $vv) {
                dump($vv);
            }
        }
        die();
         */
        return array( 'result' => $result);
    }
}
