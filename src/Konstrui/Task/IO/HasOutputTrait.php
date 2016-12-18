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
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Internal setter for output.
     *
     * @param mixed $output
     */
    protected function setOutput($output)
    {
        $this->output = $output;
    }
}
