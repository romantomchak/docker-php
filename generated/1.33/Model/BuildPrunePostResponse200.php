<?php

/*
 * This file has been auto generated by Jane,
 *
 * Do no edit it directly.
 */

namespace Docker\API\V1_33\Model;

class BuildPrunePostResponse200
{
    /**
     * @var int
     */
    protected $spaceReclaimed;

    /**
     * @return int
     */
    public function getSpaceReclaimed()
    {
        return $this->spaceReclaimed;
    }

    /**
     * @param int $spaceReclaimed
     *
     * @return self
     */
    public function setSpaceReclaimed($spaceReclaimed = null)
    {
        $this->spaceReclaimed = $spaceReclaimed;

        return $this;
    }
}