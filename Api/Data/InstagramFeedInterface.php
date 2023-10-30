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

namespace MageINIC\InstagramFeed\Api\Data;

/**
 * interface Instagram Feed Interface
 */
interface InstagramFeedInterface
{
    public const INSTAGRAMFEED = 'instagramfeed_enable';
    public const TOTAL_NUMBER = 'total_number';
    public const LAYOUT = 'layout';
    public const NUMBER_ROW = 'number_row';
    public const SHOW_CAPTION = 'show_caption';
    public const SHOW_POPUP = 'show_popup';

    /**
     * Get Instagram Feed
     *
     * @return bool
     */
    public function getInstragramFeed(): bool;

    /**
     * Get total Number
     *
     * @return mixed
     */
    public function getTotalNumber(): mixed;

    /**
     * Get layout
     *
     * @return mixed
     */
    public function getLayout(): mixed;

    /**
     * Get number Row
     *
     * @return mixed
     */
    public function getNumberRow(): mixed;

    /**
     * Get show Caption
     *
     * @return mixed
     */
    public function getShowCaption(): mixed;

    /**
     * Get show Popup
     *
     * @return mixed
     */
    public function getShowPopup(): mixed;

    /**
     * @param $instragramfeed
     * @return bool
     */
    public function setInstragramFeed($instragramfeed): bool;

    /**
     * Set instragram Feed
     *
     * @param $totalnumber
     * @return mixed
     */
    public function setTotalNumber($totalnumber): mixed;

    /**
     * Set layout
     *
     * @param $layout
     * @return mixed
     */
    public function setLayout($layout): mixed;

    /**
     * Set number row
     *
     * @param $numberrow
     * @return mixed
     */
    public function setNumberRow($numberrow): mixed;

    /**
     * Set show cation
     *
     * @param $showcaption
     * @return mixed
     */
    public function setShowCaption($showcaption): mixed;

    /**
     * Set show popup
     *
     * @param $showpopup
     * @return mixed
     */
    public function setShowPopup($showpopup): mixed;
}
