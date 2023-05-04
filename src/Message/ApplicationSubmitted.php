<?php

namespace App\Message;

use App\Entity\Application;

class ApplicationSubmitted
{
    private int $applicationId;

    public function __construct(Application $application)
    {
        $this->applicationId = $application->getId();
    }

    public function getApplicationId(): int
    {
        return $this->applicationId;
    }
}
