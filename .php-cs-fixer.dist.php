<?php

$header = <<<'EOF'
This file is part of Facturama PHP SDK.

(c) Facturama <dev@facturama.com>

This source file is subject to a MIT license that is bundled
with this source code in the file LICENSE.
EOF;

return (new PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PHP83Migration' => true,
        '@PHP82Migration:risky' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'dir_constant' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedException', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp']],
        'header_comment' => ['header' => $header],
        'heredoc_to_nowdoc' => true,
        'linebreak_after_opening_tag' => true,
        'mb_str_functions' => false,
        'modernize_types_casting' => true,
        'no_empty_comment' => true,
        'no_extra_blank_lines' => ['tokens' => [
            'break',
            'continue',
            'extra',
            'return',
            'throw',
            'use',
            'parenthesis_brace_block',
            'square_brace_block',
            'curly_brace_block',
        ]],
        'echo_tag_syntax' => ['format' => 'long'],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => false,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'php_unit_construct' => true,
        'php_unit_strict' => true,
        'psr_autoloading' => true,
        'declare_strict_types' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([__DIR__.'/src', __DIR__.'/tests', __DIR__.'/config', __DIR__.'/examples'])
    )
;
