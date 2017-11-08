<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Survey;

class EmbedController
{

    public function showAction(Survey $survey): array
    {
        {
            return [
                'survey' => $survey,
            ];
        }
    }
}