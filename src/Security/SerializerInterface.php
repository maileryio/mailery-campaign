<?php

namespace Mailery\Campaign\Security;

interface SerializerInterface
{

    /**
     * @param mixed $data
     * @return string
     */
    public function serialize(mixed $data): string;

    /**
     * @param string $data
     * @return mixed
     */
    public function deserialize(string $data): mixed;

}
