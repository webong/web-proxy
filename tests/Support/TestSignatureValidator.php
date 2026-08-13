<?php

declare(strict_types=1);

namespace Webong\WebProxy\Tests\Support;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

final class TestSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header('X-Test-Signature');

        return is_string($signature)
            && $config->signingSecret !== ''
            && hash_equals($config->signingSecret, $signature);
    }
}
