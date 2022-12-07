<?php

namespace Mailery\Campaign\Security;

class MappedSerializer implements SerializerInterface
{

    /**
     * @param array $map
     */
    public function __construct(
        private array $map
    ) {}

    /**
     * @inheritdoc
     */
    public function serialize(mixed $data): string
    {
        if (is_array($data)) {
            foreach ($this->map as $key => $alias) {
                if (!isset($data[$key])) {
                    continue;
                }

                $data[$alias] = $data[$key];
                unset($data[$key]);
            }
        }

        return serialize($data);
    }

    /**
     * @inheritdoc
     */
    public function deserialize(string $data): mixed
    {
        $data = unserialize($data);

        if (is_array($data)) {
            foreach ($this->map as $key => $alias) {
                if (!isset($data[$alias])) {
                    continue;
                }

                $data[$key] = $data[$alias];
                unset($data[$alias]);
            }
        }

        return $data;
    }

}
