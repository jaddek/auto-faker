<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use InvalidArgumentException;
use Jaddek\AutoFaker\IFakerAttribute;

/**
 * @psalm-api
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final readonly class StringFaker implements IFakerAttribute
{
    /**
     * @param int $length The desired maximum length of the generated string.
     * @psalm-param positive-int $length
     * @throws InvalidArgumentException If the provided length is not a positive integer.
     */
    public function __construct(
        private int $length = 255
    ) {
    }

    /**
     * @psalm-return string
     * @return string
     */
    #[\Override]
    public function __invoke(): string
    {
        /** @var positive-int $randomBytesLength */
        $randomBytesLength = (int)ceil((float)$this->length * 0.75);

        try {
            $randomString = base64_encode(random_bytes($randomBytesLength));
        } catch (\Exception $e) {
            $randomString = sha1((string)microtime(true) . uniqid('', true));
        }

        $generatedString = str_pad($randomString, $this->length, 'a', STR_PAD_RIGHT);

        return substr($generatedString, 0, $this->length);
    }
}