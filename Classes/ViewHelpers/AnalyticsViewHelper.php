<?php
namespace Dagou\Google\ViewHelpers;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Core\RequestId;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3Fluid\Fluid\Core\Exception;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class AnalyticsViewHelper extends AbstractViewHelper {
    protected AssetCollector $assetCollector;

    public function __construct(private readonly RequestId $requestId) {}

    /**
     * @param \TYPO3\CMS\Core\Page\AssetCollector $assetCollector
     */
    public function injectAssetCollector(AssetCollector $assetCollector) {
        $this->assetCollector = $assetCollector;
    }

    public function initializeArguments() {
        $this->registerArgument('id', 'string', 'Tracking/Measurement ID', TRUE);
    }

    public function render() {
        if (!Environment::getContext()->isDevelopment()) {
            if (!preg_match('/^(?:UA-\d+-\d+|G-\w+)$/', $this->arguments['id'])) {
                throw new Exception('Invalid tracking id(UA-XXXXXXXX-XX) or measurement id(G-XXXXXXXX).', 1669343682);
            }

            $this->assetCollector->addJavaScript(
                'google.analytics.'.$this->arguments['id'],
                '//www.googletagmanager.com/gtag/js?id='.$this->arguments['id'],
                [
                    'async' => TRUE,
                    'nonce' => $this->requestId->nonce->consume(),
                ]
            );
            $this->assetCollector->addInlineJavaScript(
                'google.analytics.'.$this->arguments['id'].'.config',
                'window.dataLayer = window.dataLayer || [];'
                    .'function gtag(){dataLayer.push(arguments);}'
                    .'gtag(\'js\', new Date());'
                    .'gtag(\'config\', \''.$this->arguments['id'].'\');',
                [
                    'nonce' => $this->requestId->nonce->consume(),
                ]
            );
        }
    }
}