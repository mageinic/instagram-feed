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

namespace MageINIC\InstagramFeed\Model;

use MageINIC\InstagramFeed\Api\Data\InstagramFeedInterface;
use Magento\Framework\DataObject;

/**
 * MageINIC_InstagramFeed class InstagramFeedModal
 */
class InstagramFeedModal extends DataObject implements InstagramFeedInterface
{
    /**
     * @inheritdoc
     */
    public function getInstragramFeed(): bool
    {
        return $this->getData(self::INSTAGRAMFEED);
    }

    /**
     * @inheritdoc
     */
    public function getTotalNumber(): mixed
    {
        return $this->getData(self::TOTAL_NUMBER);
    }

    /**
     * @inheritdoc
     */
    public function getLayout(): mixed
    {
        return $this->getData(self::LAYOUT);
    }

    /**
     * @inheritdoc
     */
    public function getNumberRow(): mixed
    {
        return $this->getData(self::NUMBER_ROW);
    }

    /**
     * @inheritdoc
     */
    public function getShowCaption(): mixed
    {
        return $this->getData(self::SHOW_CAPTION);
    }

    /**
     * @inheritdoc
     */
    public function getShowPopup(): mixed
    {
        return $this->getData(self::SHOW_POPUP);
    }

    /**
     * @inheritdoc
     */
    public function setInstragramFeed($instragramfeed): bool
    {
        return $this->getData(self::INSTAGRAMFEED, $instragramfeed);
    }

    /**
     * @inheritdoc
     */
    public function setTotalNumber($totalnumber): mixed
    {
        return $this->getData(self::TOTAL_NUMBER, $totalnumber);
    }

    /**
     * @inheritdoc
     */
    public function setLayout($layout): mixed
    {
        return $this->getData(self::LAYOUT, $layout);
    }

    /**
     * @inheritdoc
     */
    public function setNumberRow($numberrow): mixed
    {
        return $this->getData(self::NUMBER_ROW, $numberrow);
    }

    /**
     * @inheritdoc
     */
    public function setShowCaption($showcaption): mixed
    {
        return $this->getData(self::SHOW_CAPTION, $showcaption);
    }

    /**
     * @inheritdoc
     */
    public function setShowPopup($showpopup): mixed
    {
        return $this->getData(self::SHOW_POPUP, $showpopup);
    }
}
