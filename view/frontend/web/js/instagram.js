/**
 * MageINIC
 * Copyright (C) 2022 MageINIC <info@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_InstagramFeed
 * @copyright Copyright (c) 2022 MageINIC (http://www.mageinic.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <info@mageinic.com>
 */
define([
    "jquery",
    "MageINIC_InstagramFeed/js/lib/shuffle.min",
    "MageINIC_InstagramFeed/js/lib/imagesloaded.pkgd.min",
    "mageinic/instagramfeed/jquery/popup"
], function ($, Shuffle) {
    "use strict";
    $.widget("mageinic.instagram", {
        options: {
            id: "",
            token: "",
            count: "",
            sort: "",
            image_resolution: "",
            layout: "",
            show_like_comment: 0,
            show_popup: 0,
        },
        _create: function () {
            this._ajaxSubmit();
        },

        showPopup: function (id) {
            $(id).magnificPopup({
                delegate: ".instagramfeed-photo a",
                type: "image",
                gallery: {enabled: true},
                closeOnContentClick: true,
                closeBtnInside: false,
                fixedContentPos: true,
                mainClass: "mfp-no-margins mfp-with-zoom",
                image: {
                    verticalFit: true
                },
                zoom: {
                    enabled: true,
                    duration: 300
                },
            });
        },

        _ajaxSubmit: function () {
            var self = this,
                id = "#instagramfeed-photos-" + this.options.id,
                captionHtml = this.options.show_caption === '1' ? '<div class="instagramfeed-post-caption">' +
                    '{{caption}}</div>' : '',
                photo_Template = '<div class="instagramfeed-photo">' +
                    '<a class="instagramfeed-post-url" href="{{link}}" target="_blank">' +
                    captionHtml +
                    '<img class="instagramfeed-image" src="{{imgSrc}}" alt="">' +
                    '</a></div>';
            $.ajax({
                url: this.options.url,
                dataType: "json",
                type: "GET",
                success: function (data) {
                    var Image_url, item_Link,
                        items = data.data,
                        count = 0;
                    $.each(items, function (index, item) {

                        if (count >= parseInt(self.options.count)) {
                            return false;
                        }

                        if (item.media_type === 'VIDEO') {
                            return;
                        }

                        Image_url = item.media_url;
                        if (self.options.show_popup === "1") {
                            item_Link = Image_url;
                        } else {
                            item_Link = item.permalink;
                        }

                        var photo_Temp = photo_Template
                            .replace("{{link}}", item_Link)
                            .replace("{{caption}}", item.caption ? item.caption : '')
                            .replace("{{imgSrc}}", Image_url);

                        $(id).append(photo_Temp);
                        count++;
                    });
                },
                complete: function (data) {
                    // use shuffle after load images
                    if (self.options.layout === "optimized") {
                        self.demo(id);
                    }
                    if (self.options.show_popup === "1") {
                        self.showPopup(id);
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        },

        demo: function (id) {
            var element = document.querySelector(id);
            $(element).imagesLoaded().done(function (instance) {
                this.shuffle = new Shuffle(element, {
                    itemSelector: '.instagramfeed-photo'
                });
            });
        }
    });

    return $.mageinic.instagram;
});
