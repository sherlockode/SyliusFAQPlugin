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
        
        // Backward compatibility: $countUnit is only supporter by Symfony > 5.4
        if (Kernel::MAJOR_VERSION > 5 || Kernel::MAJOR_VERSION === 5 && Kernel::MINOR_VERSION > 4) {
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
                $exactMessage,
                $minMessage,
                $maxMessage,
                $charsetMessage,
                $groups,
                $payload,
                $options
            );}
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return LengthValidator::class;
    }
}
