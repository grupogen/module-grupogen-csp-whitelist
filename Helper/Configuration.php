<?php

namespace CtiDigital\CspWhitelist\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Configuration extends AbstractHelper
{

    const CONFIG_ENABLE = 'csp/general/enabled';
    const CONFIG_POLICIES = 'csp/general/policies';
    const CONFIG_SECURE_SCRIPTS_ENABLE = 'csp/secure_scripts/enabled';
    const CONFIG_SECURE_SCRIPTS = 'csp/secure_scripts/scripts';

    /** @var Json */
    private $serialize;

    /**
     * @param Context $context
     * @param Json $serialize
     */
    public function __construct(
        Context $context,
        Json $serialize
    ) {
        $this->serialize = $serialize;
        parent::__construct($context);
    }

    /**
     * check to see if module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_ENABLE);
    }

    /**
     * check to see if module is enabled
     *
     * @return bool
     */
    public function isSecureScriptsEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_SECURE_SCRIPTS_ENABLE,ScopeInterface::SCOPE_STORE,$storeId);
    }

    /**
     * get a list of policies
     *
     * @return array
     */
    public function getPolicies(): array
    {
        $data = [];
        $policies = $this->scopeConfig->getValue(self::CONFIG_POLICIES);
        if (!$policies) {
            return $data;
        }
        foreach ($this->serialize->unserialize($policies) as $policy) {
            $data[$policy['policy']][] = $policy['value'];
        }
        return $data;
    }

    /**
     * get a list of policies
     *
     * @return string
     */
    public function getScripts($storeId = null): string
    {
        $scripts = '';
        $scripts = $this->scopeConfig->getValue(self::CONFIG_SECURE_SCRIPTS,ScopeInterface::SCOPE_STORE,$storeId);
        return $scripts;
    }
}
