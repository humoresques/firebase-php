<?php

declare(strict_types=1);

namespace Kreait\Firebase\Value;

use function GuzzleHttp\Psr7\uri_for;
use Kreait\Firebase\Exception\InvalidArgumentException;
use Kreait\Firebase\Value;
use Psr\Http\Message\UriInterface;
use Throwable;

class Url implements \JsonSerializable, Value
{
    /** @var UriInterface */
    private $value;

    /**
     * @internal
     */
    public function __construct(UriInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @param self|UriInterface|string $value
     *
     * @throws InvalidArgumentException
     */
    public static function fromValue($value): self
    {
        try {
            return new self(uri_for((string) $value));
        } catch (Throwable $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    public function toUri(): UriInterface
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function jsonSerialize(): string
    {
        return (string) $this->value;
    }

    public function equalsTo($other): bool
    {
        return (string) $this->value === (string) $other;
    }
}
