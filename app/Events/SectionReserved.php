<?php

namespace App\Events;

class SectionReserved
{
    /**
     * @var int
     */
    private $sectionId;

    /**
     * @param int $sectionId
     */
    public function __construct(int $sectionId)
    {
        $this->sectionId = $sectionId;
    }

    /**
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionId;
    }
}
