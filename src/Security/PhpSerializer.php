<?php

namespace Mailery\Campaign\Security;

class PhpSerializer implements SerializerInterface
{

    /**
     * @inheritdoc
     */
    public function serialize(mixed $data): string
    {
        return serialize($data);
    }

    /**
     * @inheritdoc
     */
    public function deserialize(string $data): mixed
    {
        return unserialize($data);
    }

}
