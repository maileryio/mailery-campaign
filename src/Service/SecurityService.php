<?php

namespace Mailery\Campaign\Service;

use Yiisoft\Security\Crypt;
use Mailery\Campaign\Security\PhpSerializer;
use Mailery\Campaign\Security\SerializerInterface;

class SecurityService
{

    /**
     * @param string $encryptKey
     * @param SerializerInterface|null $serilizer
     */
    public function __construct(
        private string $encryptKey,
        private ?SerializerInterface $serilizer = null
    ) {
        if ($this->serilizer === null) {
            $this->serilizer = new PhpSerializer();
        }
    }

    /**
     * @param SerializerInterface $serilizer
     * @return self
     */
    public function withSerializer(SerializerInterface $serilizer): self
    {
        $new = clone $this;
        $new->serilizer = $serilizer;

        return $new;
    }

    /**
     * @param array $params
     * @return string
     */
    public function encrypt(array $params): string
    {
        return base64_encode(
            (new Crypt())->encryptByKey(
                $this->serilizer->serialize($params),
                $this->encryptKey
            )
        );
    }

    /**
     * @param string $string
     * @return array
     */
    public function decrypt(string $string): array
    {
        return $this->serilizer->deserialize(
            (new Crypt())->decryptByKey(
                base64_decode($string),
                $this->encryptKey
            )
        );
    }

}
