<?php
namespace Rohmattaufik\Allocation;

use Exception;
use stdClass;

class RoundRobbin {

    /** @var stdClass[] $allocations */
    public $allocations;

    function __construct($allocations = [])
    {
        $this->allocations = $allocations;
    }

    /**
     * @param string $key
     * @param int $weight
     * @param int|null $remaining
     * @return void
     * @throws Exception
     */
    function setAllocation($key, $weight, $remaining = null)
    {
        if ($weight < 1)
            throw new Exception("Weight must greater than 0");

        $allocation = new stdClass();
        $allocation->key = $key;
        $allocation->weight = $weight;
        $allocation->remaining = $weight;
        if ($remaining !== null && $remaining >= 0)
            $allocation->remaining = $remaining;
        $this->allocations[$key] = $allocation;
    }

    /**
     * @return stdClass[]
     */
    function getAllocation()
    {
        return array_values($this->allocations);
    }

    /**
     * @return stdClass
     * @throws Exception
     */
    function allocate()
    {
        if (count($this->allocations) === 0)
            throw new Exception("No allocations data");

        $candidates = $this->findCandidates();
        if (count($candidates) === 0)
        {
            $this->reset();
            $candidates = $this->findCandidates();
        }

        $selectedKey = null;
        $selectedDiff = 0;
        foreach ($candidates as $candidate)
        {
            $diff = $candidate->weight - $candidate->remaining;
            if ($selectedKey === null || $diff < $selectedDiff)
            {
                $selectedKey = $candidate->key;
                $selectedDiff = $diff;
                continue;
            }
        }

        $this->allocations[$selectedKey]->remaining -= 1;
        
        return $this->allocations[$selectedKey];
    }

    /**
     * @return void
     */
    function reset()
    {
        foreach ($this->allocations as $key=>$allocation)
            $this->allocations[$key]->remaining = $allocation->weight;
    }

    /**
     * @return stdClass[]
     */
    function findCandidates()
    {
        return array_filter($this->allocations, function($allocation){
            return $allocation->remaining > 0;
        });
    }
}