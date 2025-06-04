<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use InvalidArgumentException;
use Jaddek\AutoFaker\IFakerAttribute;
use Random\RandomException;

/**
 * @psalm-api
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class EmailFaker implements IFakerAttribute
{
    private const string RANDOM_CHARS = 'abcdefghijklmnopqrstuvwxyz0123456789';
    private const string DEFAULT_RANDOM_DOMAIN = 'random';
    private const string DEFAULT_RANDOM_TLD = 'random';
    private const int DEFAULT_LENGTH = 10;

    /**
     * @var string[]
     */
    private const array DEFAULT_TLDS = ['.com', '.org', '.net', '.info', '.biz', '.io', '.co'];

    /**
     * @param string $domain
     * @param string $tld
     * @psalm-param non-empty-string $domain
     * @psalm-param non-empty-string $tld
     */
    public function __construct(
        private string $domain = self::DEFAULT_RANDOM_DOMAIN,
        private string $tld = self::DEFAULT_RANDOM_TLD,
    )
    {
        if (empty($this->domain)) {
            throw new InvalidArgumentException('Domain cannot be empty.');
        }
        if (empty($this->tld)) {
            throw new InvalidArgumentException('TLD cannot be empty.');
        }
    }

    /**
     * @psalm-return non-empty-string
     * @return string The generated email address.
     * @throws RandomException
     */
    #[\Override]
    public function __invoke(): string
    {
        $localPart = $this->generateRandomString(8) . '.' . $this->generateRandomString(5);

        $resolvedDomain = $this->domain === self::DEFAULT_RANDOM_DOMAIN ? $this->generateRandomString() : $this->domain;
        $resolvedTld = $this->tld === self::DEFAULT_RANDOM_TLD ? self::DEFAULT_TLDS[array_rand(self::DEFAULT_TLDS)] : $this->tld;

        if ($resolvedTld !== self::DEFAULT_RANDOM_TLD && !str_starts_with($resolvedTld, '.')) {
            $resolvedTld = '.' . $resolvedTld;
        }

        return sprintf('%s@%s%s', $localPart, $resolvedDomain, $resolvedTld);
    }

    /**
     * @param int $length The desired length of the random string.
     * @psalm-param positive-int $length
     * @psalm-return string
     * @return string
     * @throws \InvalidArgumentException
     * @throws RandomException
     */
    private function generateRandomString(int $length = self::DEFAULT_LENGTH): string
    {
        /** @psalm-suppress DocblockTypeContradiction */
        if ($length <= 0) {
            throw new \InvalidArgumentException('Length must be a positive integer.');
        }
        $randomString = '';
        $charactersLength = strlen(self::RANDOM_CHARS);

        for ($i = 0; $i < $length; $i++) {
            $randomString .= self::RANDOM_CHARS[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}