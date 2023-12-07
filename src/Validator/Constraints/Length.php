<?php

namespace Sherlockode\SyliusFAQPlugin\Validator\Constraints;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints\Length as BaseLength;
use Symfony\Component\Validator\Constraints\LengthValidator;

class Length extends BaseLength
{
    public function __construct(
        int|array $exactly = null,
        int $min = null,
        int $max = null,
        string $charset = null,
        callable $normalizer = null,
        string $countUnit = null,
        string $exactMessage = null,
        string $minMessage = null,
        string $maxMessage = null,
        string $charsetMessage = null,
        array $groups = null,
        mixed $payload = null,
        array $options = []
    ) {

        if (null === $normalizer) {
            $normalizer = function(string $str) {
                return strip_tags(trim($str));
            };
        }

        if (Kernel::VERSION_ID >= 60300) {
            parent::__construct(
                $exactly,
                $min,
                $max,
                $charset,
                $normalizer,
                $countUnit,
                $exactMessage,
                $minMessage,
                $maxMessage,
                $charsetMessage,
                $groups,
                $payload,
                $options
            );
        } else {
            parent::__construct(
                $exactly,
                $min,
                $max,
                $charset,
                $normalizer,
                $exactMessage,
                $minMessage,
                $maxMessage,
                $charsetMessage,
                $groups,
                $payload,
                $options
            );
        }
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return LengthValidator::class;
    }
}
