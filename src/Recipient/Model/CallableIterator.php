<?php

namespace Mailery\Campaign\Recipient\Model;

class CallableIterator implements \Iterator
{

    /**
     * @param \Iterator $innerIterator
     * @param \Closure $callback
     */
    public function __construct(
        private \Iterator $innerIterator,
        private \Closure $callback
    ) {}

    /**
     * @return mixed
     */
    public function current(): mixed
    {
        $currentElement = $this->innerIterator->current();

        return \call_user_func($this->callback, $currentElement);
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->innerIterator->next();
    }

    /**
     * @return mixed
     */
    public function key(): mixed
    {
        return $this->innerIterator->key();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->innerIterator->valid();
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->innerIterator->rewind();
    }

}
