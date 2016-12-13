<?php

namespace Konstrui\Task\IO;

/**
 * Trait implementing HasOutputInterface.
 */
trait HasOutputTrait
{
    /** @var mixed */
    protected $output;

    /**
     * Internal setter for output.
     *
     * @param mixed $output
     */
    protected function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }
}
