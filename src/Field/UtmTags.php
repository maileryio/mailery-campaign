<?php

namespace Mailery\Campaign\Field;

class UtmTags
{

    /**
     * @var array
     */
    private array $values = [];

    /**
     * @return string
     */
    public function __toString(): string
    {
        return http_build_query(array_filter($this->values));
    }

    /**
     * @param string $value
     * @return static
     */
    public static function typecast(string $value): static
    {
        $values = [];
        parse_str($value, $values);

        return (new static())
            ->setSource($values['utm_source'] ?? null)
            ->setMedium($values['utm_medium'] ?? null)
            ->setCampaign($values['utm_campaign'] ?? null)
            ->setContent($values['utm_content'] ?? null)
            ->setTerm($values['utm_term'] ?? null);
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->values['utm_source'] ?? null;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setSource(?string $value): self
    {
        $new = clone $this;
        $new->values['utm_source'] = $value;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getMedium(): ?string
    {
        return $this->values['utm_medium'] ?? null;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setMedium(?string $value): self
    {
        $new = clone $this;
        $new->values['utm_medium'] = $value;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getCampaign(): ?string
    {
        return $this->values['utm_campaign'] ?? null;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setCampaign(?string $value): self
    {
        $new = clone $this;
        $new->values['utm_campaign'] = $value;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->values['utm_content'] ?? null;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setContent(?string $value): self
    {
        $new = clone $this;
        $new->values['utm_content'] = $value;

        return $new;
    }

    /**
     * @return string|null
     */
    public function getTerm(): ?string
    {
        return $this->values['utm_term'] ?? null;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setTerm(?string $value): self
    {
        $new = clone $this;
        $new->values['utm_term'] = $value;

        return $new;
    }

}
