<?php

/*
 * Code is distributed as-is
 * the Developer may change the code at its discretion without prior notice
 * Developers: Djo 
 * Website: http://zixn.ru
 * Twitter: https://twitter.com/Zixnru
 * Email: izm@zixn.ru
 */

class BuyShortcode {

    /**
     * Кнопка купить
     * Возможно размещение только в цикле вывода товаров
     */
    public function __construct() {
        if (!shortcode_exists('viewBuyButton')) {
            add_shortcode('viewBuyButton', array($this, 'viewBuyButton'));
        }
    }

    public function viewBuyButton() {
        $core = new BuyCore();
        $core->styleAddFrontPage();
        $core->scriptAddFrontPage();
        return BuyFunction::viewBuyButton();
    }

}
