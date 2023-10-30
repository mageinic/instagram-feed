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

use MageINIC\InstagramFeed\Api\ConfigApiInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * MageINIC_InstagramFeed class InstagramFeed
 */
class InstagramFeed implements ConfigApiInterface
{
    public const INSTAGRAMFEED = 'instagramfeed/general/enabled';
    public const TOTAL_NUMBER = 'instagramfeed/display/total_number';
    public const LAYOUT = 'instagramfeed/display/layout';
    public const NUMBER_ROW = 'instagramfeed/display/number_row';
    public const SHOW_CAPTION = 'instagramfeed/display/show_caption';
    public const SHOW_POPUP = 'instagramfeed/display/show_popup';

    /**
     * @var WriterInterface
     */
    protected WriterInterface $configWriter;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * InstagramFeed Constructor
     *
     * @param WriterInterface $configWriter
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        WriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function getConfigValues(): array
    {
        $configValues[] = [
            'instagramfeed_enable' => $this->scopeConfig->getValue(self::INSTAGRAMFEED),
            'total_number' => $this->scopeConfig->getValue(self::TOTAL_NUMBER),
            'layout' => $this->scopeConfig->getValue(self::LAYOUT),
            'number_row' => $this->scopeConfig->getValue(self::NUMBER_ROW),
            'show_caption' => $this->scopeConfig->getValue(self::SHOW_CAPTION),
            'show_popup' => $this->scopeConfig->getValue(self::SHOW_POPUP),
        ];
        return $configValues;
    }
}
