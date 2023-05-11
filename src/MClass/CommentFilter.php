<?php

namespace App\MClass;

use App\Enum\CommentStateType;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class CommentFilter{
    public ?CommentStateType $state = CommentStateType::HAM; // CommentStateType::DRAFT
    private ?\DateTime $startDate;
    // ДВА ДНЯ!!!
    #[Assert\GreaterThanOrEqual(propertyPath:"startDate")]
    public ?\DateTime $endDate;

    public function getStartDate() :DateTime
    {
        return $this->startDate;
    }
    public function setStartDate( DateTime $value): self
    {
        $this->startDate = $value;
        return $this;
    }
}
