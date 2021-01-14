<?php


namespace App\server\services;


class ParserPage
{
    /**
     * @param string $userTicker Строка с такером актива.
     * @param int $type Вид актива приходящего для запроса. 1-акция, 2-фонд, 3-облизация, 4-валюта
     * @return array|bool Результат запроса. Массив с результатами. Поля(name, ticker, urlImg).
     */
    public function getAsset($userTicker, $type = 1)
    {
        $src = file_get_contents($this->createSrcString($userTicker, $type));
        $content = $this->getContentFromSite($src);

        if (!($content['name'] && $content['ticker'])) return false;

            preg_match('/(?<=url\(\/\/)(.*\.\w*)/', $content['url_img'], $imgUrl);
        $content['url_img'] = 'http://' . $imgUrl[0];

        return $content;
    }

    private function getContentFromSite($src)
    {
        $selectorName = '.SecurityHeaderPure__showName_3trmM';
        $selectorTicker = '.SecurityHeaderPure__ticker_2EYMk';
        $selectorImg = '.Avatar-module__image_2WFrC';
        $dom = \phpQuery::newDocument($src);

        return [
            'name' => $dom->find($selectorName)->text(),
            'ticker' => $dom->find($selectorTicker)->text(),
            'url_img' => $dom->find($selectorImg)->attr('style'),
        ];
    }

    private function createSrcString($userTicker, $type)
    {
        $str = 'https://www.tinkoff.ru/invest/';
        switch ($type) {
            case 1: // акции
                $str .= "stocks/";
                break;
            case 2: // фонды
                $str .= "etfs/";
                break;
            case 3: // облигации
                $str .= "bonds/";
                break;
            case 4: // валюта
                $str .= "currencies/";
                break;
        }
        $str .= strtoupper($userTicker);
        return $str;
    }
}