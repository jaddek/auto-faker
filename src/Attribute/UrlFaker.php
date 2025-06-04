<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;


use Jaddek\AutoFaker\IFakerAttribute;
use Psr\Log\InvalidArgumentException;
use Random\RandomException;

/**
 * @psalm-api
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final readonly class UrlFaker implements IFakerAttribute
{
    private const string RANDOM_CHARS = 'abcdefghijklmnopqrstuvwxyz0123456789';
    private const int DEFAULT_LENGTH = 10;
    /**
     * @var string[]
     */
    private const DEFAULT_TLDS = ['.com', '.org', '.net', '.info', '.biz', '.io', '.co'];

    /**
     * @param bool $secure
     * @param string $domain
     * @param string $tld
     * @psalm-param non-empty-string $domain
     * @psalm-param non-empty-string $tld
     */
    public function __construct(
        private bool   $secure = false,
        private string $domain = 'random',
        private string $tld = 'random',
    )
    {
        if (empty($this->domain)) {
            throw new \InvalidArgumentException('Domain cannot be empty.');
        }
        if (empty($this->tld)) {
            throw new \InvalidArgumentException('TLD cannot be empty.');
        }
    }

    /**
     * @psalm-return non-empty-string
     * @return string The generated URL.
     */
    #[\Override]
    public function __invoke(): string
    {
        $schema = $this->secure ? 'https' : 'http';

        $resolvedDomain = $this->domain === 'random' ? $this->generateRandomString() : $this->domain;
        $resolvedTld = $this->tld === 'random' ? self::DEFAULT_TLDS[array_rand(self::DEFAULT_TLDS)] : $this->tld;

        if ($resolvedTld !== 'random' && !str_starts_with($resolvedTld, '.')) {
            $resolvedTld = '.' . $resolvedTld;
        }

        return sprintf('%s://%s%s', $schema, $resolvedDomain, $resolvedTld);
    }

    /**
     * @param int $length The desired length of the random string.
     * @psalm-param positive-int $length
     * @psalm-return string
     * @return string
     * @throws InvalidArgumentException
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