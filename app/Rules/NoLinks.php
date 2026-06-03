<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoLinks implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/(https?:\/\/[^\s]+|www\.[^\s]+|<\s*a\s+[^>]*href\s*=|<a\s*>|\[url=)/i', $value)) {
            $fail('The :attribute must not contain links or URLs.');
        }
    }
}
