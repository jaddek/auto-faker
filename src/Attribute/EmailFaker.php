<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class EmailFaker implements IFakerAttribute
{
    public function __construct(
        private string $domain = 'random-domain',
        private string $tld = '.cxyz',
    )
    {

    }

    public function __invoke(): string
    {
        $data = [
            '{id}'     => substr(sha1((string)time()), 0, 10),
            '{domain}' => $this->domain,
            '{tld}'    => $this->tld,
        ];

        return strtr('{id}@{domain}{tld}', $data);
    }
}