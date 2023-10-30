<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_InstagramFeed
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\InstagramFeed\Helper;

use Exception;
use Magento\Backend\App\Config;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;
use Magento\Store\Model\ScopeInterface;
use Magento\Backend\App\ConfigInterface;

/**
 * Class Helper Data to get system configuration values
 */
class Data extends AbstractHelper
{
    const CONFIG_MODULE_PATH = 'instagramfeed';

    /**
     * @var State
     */
    private State $state;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Data constructor
     *
     * @param Context $context
     * @param State $state
     * @param ConfigInterface $config
     */
    public function __construct(
        Context $context,
        State $state,
        ConfigInterface $config
    ) {
        parent::__construct($context);
        $this->state = $state;
        $this->config = $config;
    }

    /**
     * Get Enable
     *
     * @return mixed
     */
    public function isEnabled(): mixed
    {
        return $this->scopeConfig->getValue('instagramfeed/general/enabled', ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Access Token
     *
     * @return mixed
     */
    public function getAccessToken(): mixed
    {
        return $this->scopeConfig->getValue('instagramfeed/general/access_token', ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Config General
     *
     * @param string $code
     * @param $storeId
     * @return array|mixed
     */
    public function getConfigGeneral(string $code = '', $storeId = null): mixed
    {
        $code = ($code !== '') ? '/' . $code : '';
        return $this->getConfigValue(static::CONFIG_MODULE_PATH . '/general' . $code, $storeId);
    }

    /**
     * Get Module Config
     *
     * @param string $field
     * @param null $storeId
     * @return mixed
     */
    public function getModuleConfig(string $field = '', $storeId = null): mixed
    {
        $field = ($field !== '') ? '/' . $field : '';
        return $this->getConfigValue(static::CONFIG_MODULE_PATH . $field, $storeId);
    }

    /**
     * Get Config Value
     *
     * @param $field
     * @param null $scopeValue
     * @param string $scopeType
     * @return array|mixed
     */
    public function getConfigValue($field, $scopeValue = null, string $scopeType = ScopeInterface::SCOPE_STORE): mixed
    {
        if ($scopeValue === null && !$this->isArea()) {
            /** @var Config $backendConfig */
            if (!$this->backendConfig) {
                $this->backendConfig = $this->config->getValue(ConfigInterface::class);
            }
            return $this->backendConfig->getValue($field);
        }
        return $this->scopeConfig->getValue($field, $scopeType, $scopeValue);
    }

    /**
     * Is Area
     *
     * @param string $area
     * @return mixed
     */
    public function isArea(string $area = Area::AREA_FRONTEND): mixed
    {
        if (!isset($this->isArea[$area])) {
            try {
                $this->isArea[$area] = ($this->state->getAreaCode() == $area);
            } catch (Exception $e) {
                $this->isArea[$area] = false;
            }
        }
        return $this->isArea[$area];
    }

    /**
     * Get Display Config
     *
     * @param null $storeId
     * @return mixed
     */
    public function getDisplayConfig($storeId = null): mixed
    {
        return $this->getModuleConfig('display', $storeId);
    }
}
