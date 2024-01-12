<?php
return \TYPO3\CMS\Core\Type\Map::fromEntries([
    \TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope::frontend(),
    new \TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection(
        new \TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation(
            \TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode::Extend,
            \TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive::ScriptSrc,
            new \TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue('www.googletagmanager.com'),
        ),
    )
]);
