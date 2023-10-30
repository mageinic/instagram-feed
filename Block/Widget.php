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

namespace MageINIC\InstagramFeed\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use MageINIC\InstagramFeed\Helper\Data;
use MageINIC\InstagramFeed\Model\Config\Source\Design;
use MageINIC\InstagramFeed\Model\Config\Source\Layout;

/**
 * class Widget to set widget template and get system configuration values
 */
class Widget extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'MageINIC_InstagramFeed::instagram.phtml';

    /**
     * @var Data
     */
    protected Data $helperData;

    /**
     * Widget constructor.
     *
     * @param Template\Context $context
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helperData,
        array $data = []
    ) {
        $this->helperData = $helperData;
        parent::__construct($context, $data);
    }

    /**
     * Is Enable
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->helperData->isEnabled();
    }

    /**
     * Retrieve all options for Instagram feed
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getAllOptions(): mixed
    {
        $option = (int)$this->getData('design');
        if ($option === Design::CONFIG) {
            $this->setData(array_merge($this->helperData->getDisplayConfig($this->getStoreId()), $this->getData()));
        }
        return $this->getData();
    }

    /**
     * Get NumberRow
     *
     * @param string $layoutOpt
     * @return int|mixed|null
     */
    public function getNumberRow(string $layoutOpt): mixed
    {
        switch ($layoutOpt) {
            case Layout::MULTIPLE:
                $number_row = !empty($this->getData('number_row')) ? $this->getData('number_row') : 2;
                break;
            case Layout::SINGLE:
                $number_row = 1;
                break;
            default:
                $number_row = null;
        }
        return $number_row;
    }

    /**
     * Calc Width
     *
     * @return float|int
     */
    public function calcWidth(): float|int
    {
        $type = $this->getData('layout');
        $total = $this->getData('total_number');
        $number_row = $this->getNumberRow($type);
        if (!empty($number_row)) {
            return (100 / round($total / $number_row));
        }
        return 300;
    }

    /**
     * Get Access Token
     *
     * @return mixed
     */
    public function getAccessToken(): mixed
    {
        return $this->helperData->getAccessToken();
    }

    /**
     * Get Store ID
     *
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return $this->_storeManager->getStore()->getId();
    }
}
