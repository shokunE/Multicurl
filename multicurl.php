<?php

/**
 * Created by PhpStorm.
 * User: schok
 * Date: 17.10.2017
 * Time: 9:26
 * Многопоточная обработка курлом
 */
class multicurl
{
    /**
     * @param $urls Массив урлов
     * @param $setopt Массив установок CURLOPT_HEADER => false
     * @return array
     */
    public static function go ($urls,$setopt = null)
    {
        $multi = curl_multi_init();
        $handles = [];
        foreach ($urls as $url){

            $ch = curl_init($url);

            if($setopt != null){
                foreach ($setopt as $key => $val){
                    curl_setopt($ch,constant($key),$val);
                }
            }
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

            curl_multi_add_handle($multi, $ch);
            $handles[$url] = $ch;
        }
        do {
            $mrc = curl_multi_exec($multi, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        while ($active && $mrc == CURLM_OK) {
            if(curl_multi_select($multi) == -1){
                usleep(100);
            }
            do{
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
        $html = [];
        foreach ($handles as $channel) {
            $html[] = curl_multi_getcontent($channel);
            curl_multi_remove_handle($multi, $channel);
        }
        curl_multi_close($multi);
        return $html;
    }
}