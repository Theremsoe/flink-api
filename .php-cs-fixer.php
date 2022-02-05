<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap/cache', 'storage', 'docs', 'public', 'vendor'])
    ->notName('*.blade.php')
;

$config = new PhpCsFixer\Config();

return $config->setRiskyAllowed(true)
    ->setRules([
        // @PhpCsFixer contains @PSR2 and @Symfony rulesets.
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PHP81Migration' => true,
        'not_operator_with_successor_space' => true,
        'final_internal_class' => false,
        'declare_strict_types' => false,
        'date_time_immutable' => true,
    ])
    ->setFinder($finder)
;
