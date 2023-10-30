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

namespace MageINIC\InstagramFeed\Controller\Feed;

use InvalidArgumentException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Cache;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\Exception as HttpException;
use Magento\Setup\Exception;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\InstagramFeed\Model\Cache\Type as CacheType;

/**
 * class Get to get feed url and Retrieve feed from Instagram Graph API
 */
class Get extends Action
{
    const API_URL = 'https://graph.instagram.com/me/media';

    /**
     * @var JsonFactory
     */
    protected JsonFactory $_resultJsonFactory;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @var Cache
     */
    protected Cache $_cache;

    /**
     * @var Cache\State
     */
    protected Cache\State $_cacheState;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $_scopeConfig;

    /**
     * @var Curl
     */
    protected Curl $_curl;

    /**
     * @var Json
     */
    protected Json $_json;

    /**
     * @var string
     */
    protected string $id;

    /**
     * Get Constructor
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param StoreManagerInterface $_storeManager
     * @param Cache $cache
     * @param Cache\State $cacheState
     * @param ScopeConfigInterface $scopeConfig
     * @param Curl $curl
     * @param Json $json
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        StoreManagerInterface $_storeManager,
        Cache $cache,
        Cache\State $cacheState,
        ScopeConfigInterface $scopeConfig,
        Curl $curl,
        Json $json
    ) {
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_storeManager      = $_storeManager;
        $this->_cache             = $cache;
        $this->_cacheState        = $cacheState;
        $this->_scopeConfig       = $scopeConfig;
        $this->_curl              = $curl;
        $this->_json              = $json;
        $this->id                 = $this->getId();
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|JsonResult|ResultInterface
     */
    public function execute(): JsonResult|ResultInterface|ResponseInterface
    {
        $result = $this->_resultJsonFactory->create();
        try {
            if (!$this->getRequest()->isAjax() ||
                !$this->_scopeConfig->getValue('instagramfeed/general/enabled', ScopeInterface::SCOPE_STORE)
            ) {
                return $this->_redirect('noroute');
            }
            if ($this->_cacheState->isEnabled(CacheType::TYPE_IDENTIFIER)) {
                if ($feed = $this->_cache->load($this->id)) {
                    return $result->setData($this->_json->unserialize($feed));
                }
            }
            $this->_curl->get($this->getFeedUrl());
            if ($this->_curl->getStatus() === 200) {
                if ($this->_cacheState->isEnabled(CacheType::TYPE_IDENTIFIER)) {
                    $this->save($this->_curl->getBody(), $this->id);
                    return $result->setData($this->_json->unserialize($this->_cache->load($this->id)));
                }
                return $result->setData($this->_json->unserialize($this->_curl->getBody()));
            }
            $result->setHttpResponseCode(HttpException::HTTP_BAD_REQUEST);
            return $result->setData($this->_json->unserialize($this->_curl->getBody()));
        } catch (Exception $e) {
            $result->setHttpResponseCode(HttpException::HTTP_BAD_REQUEST);
            return $result->setData(['error' => $e->getMessage()]);
        } catch (InvalidArgumentException $e) {
            $result->setHttpResponseCode(HttpException::HTTP_INTERNAL_ERROR);
            return $result->setData(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get unique cache key
     *
     * @return string
     */
    public function getId(): string
    {
        try {
            return base64_encode($this->_storeManager->getStore()->getCode() . CacheType::TYPE_IDENTIFIER);
        } catch (NoSuchEntityException $e) {
            return base64_encode(date('Y-m-d') . CacheType::TYPE_IDENTIFIER);
        }
    }

    /**
     * Load cached value if cache type is enabled
     *
     * @param $cacheId
     * @return bool|string
     */
    public function load($cacheId): bool|string
    {
        if ($this->_cacheState->isEnabled(CacheType::TYPE_IDENTIFIER)) {
            return $this->_cache->load($cacheId) ?: false;
        }
        return false;
    }

    /**
     * Save data to cache type if enabled
     *
     * @param $data
     * @param $cacheId
     * @return bool
     */
    public function save($data, $cacheId): bool
    {
        if ($this->_cacheState->isEnabled(CacheType::TYPE_IDENTIFIER)) {
            return $this->_cache->save($data, $cacheId, [CacheType::CACHE_TAG], CacheType::CACHE_LIFETIME);
        }
        return false;
    }

    /**
     * Get FeedUrl
     *
     * @return string
     */
    protected function getFeedUrl(): string
    {
        $token = $this->_scopeConfig->getValue('instagramfeed/general/access_token', ScopeInterface::SCOPE_STORE);
        return self::API_URL . '?' . http_build_query(
            ['access_token' => $token, 'fields' => 'id, caption, media_type, media_url, permalink']
        );
    }
}
