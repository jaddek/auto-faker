<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class UrlFaker implements IFakerAttribute
{
    public function __construct(
        private bool $secure = false,
        private string $domain = 'random-domain',
        private string $tld = '.cxyz',
    ) {
    }

    public function __invoke(): string
    {
        $data = [
            '{schema}' => $this->secure ? 'https' : 'http',
            '{domain}' => $this->domain,
            '{tld}'    => $this->tld,
        ];

        return strtr('{schema}://{domain}{tld}', $data);
    }
}
