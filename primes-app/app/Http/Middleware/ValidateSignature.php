<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as BaseValidator;

class ValidateSignature extends BaseValidator
{
    /**
     * The names of the parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Parámetros que no afectan la firma
        'fbclid',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_source',
        'utm_term',
    ];

    /**
     * Determine if the request has a valid signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array|null  $ignoreQuery
     * @return bool
     */
    public function hasValidSignature($request, $absolute = true)
    {
        return parent::hasValidSignature($request, $absolute);
    }
} 