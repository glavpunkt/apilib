<?php

namespace Glavpunkt;

use Exception;

/**
 * Работы с официальным API Главпункт.
 *
 * @see https://glavpunkt.ru/apidoc/index.html
 */
class GlavpunktApi
{
    private $login;
    private $token;
    private $host = 'https://glavpunkt.ru';

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
     * Передача в систему Главпункт данных о передаваемых заказах (электронная накладная).
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
     *
     * @param string $sku Номер заказа в интернет-магазине
     */
    public function pkgStatus($sku)
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
    public function pkgsList()
    {
        $data = array();
        $data['login'] = $this->login;
        $data['token'] = $this->token;

        return $this->post('/api/pkgs_list', $data);
    }

    /**
     * Возвращаются трекинг код, который однозначно идентифицирует заказ в системе Главпункта
     *
     * @param string $sku Номер заказа в интернет-магазине
     */
    public function trackCode($sku)
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
        curl_setopt($curl, CURLOPT_URL, $this->host . $url);
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
        curl_setopt($curl, CURLOPT_URL, $this->host . $url);
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
