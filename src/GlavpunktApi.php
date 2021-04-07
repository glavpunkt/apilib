<?php

namespace Glavpunkt;

/**
 * GlavpunktAPI
 *
 * 1. take_pkgs передача информации в систему Glavpukt.ru по передаваемым заказам
 * 2. punkts перечень пунктов выдачи Glavpunkt.ru
 * 3. pkg_status статус заказа или нескольких заказов
 *
 * Актуальную документацию по работе всех методов см на странице http://glavpunkt.ru/apidoc/index.html
 *
 * @version 22.05.2015
 */
class GlavpunktApi
{

    private $login;
    private $token;
    private $host = 'glavpunkt.ru';

    public function __construct($login, $token)
    {
        if (!isset($login)) {
            throw new Exception("Не указан параметр login");
        }

        if (!isset($token)) {
            throw new Exception("Не указан параметр token");
        }

        $this->login = $login;
        $this->token = $token;
    }

    /**
     * Создание поставки с заказами.
     *
     * Передача в систему Glavpunkt.ru данных о передаваемых заказах (электронная накладная).
     *
     * @param array $data
     * @return array
     */
    public function createShipment($data)
    {
        $data['login'] = $this->login;
        $data['token'] = $this->token;

        $res = $this->postJSON('/api/create_shipment', $data);

        return $res;
    }

    /**
     * Возвращает статус заказа или нескольких заказов
     */
    public function pkg_status($sku)
    {
        $data = array();
        $data['login'] = $this->login;
        $data['token'] = $this->token;
        $data['sku'] = $sku;

        return $this->post('/api/pkg_status', $data);
    }


    /**
     * Возвращает список пунктов выдач
     */
    public function punkts()
    {
        return $this->post('/api/punkts');
    }

    /**
     * Возвращает список заказов находящихся в Главпункте
     */
    public function pkgs_list()
    {
        $data = array();
        $data['login'] = $this->login;
        $data['token'] = $this->token;

        return $this->post('/api/pkgs_list', $data);
    }

    /**
     * Возвращаются трекинг код, который однозначно идентифицирует заказ в системе Главпункта
     */
    public function track_code($sku)
    {
        $data = array();
        $data['token'] = $this->token;
        $data['sku'] = $sku;

        return $this->post('/api/track_code', $data);
    }


    /**
     * Отправка HTTP-запроса POST к API Glavpunkt.ru
     */
    private function post($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://' . $this->host . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (isset($data)) {
            $post_body = http_build_query($data);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body);
        }

        $out = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($out, true);
        if (is_null($res)) {
            throw new Exception("Неверный JSON ответ: " . $out);
        }

        return $res;
    }


    /**
     * Отправка HTTP-запроса POST к API Glavpunkt.ru
     */
    private function postJSON($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://' . $this->host . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (isset($data)) {
            $post_body = json_encode($data);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }

        $out = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($out, true);
        if (is_null($res)) {
            throw new Exception("Неверный JSON ответ: " . $out);
        }

        return $res;
    }
}