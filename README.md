# Glavpunkt Api library

### Требования
    PHP >= 5.4

## Особенности
- Создание поставки с заказами
- Получение статуса заказа
- Получение списка заказов
- Получение tracing code для заказа
- Получение списка пунктов выдачи

## Установка

 Выполнить
    ```
    composer require glavpunkt/api
    ```

## Использование


1.Создание поставки с заказами
   ```php
     
use Glavpunkt\GlavpunktAPI;

$gpApi = new GlavpunktAPI(LOGIN, TOKEN);

$data = array(
    "shipment_options" => array(
        "skip_existed " => 1, // Если какой-либо из заказов уже создан, то пропустить его.
        // В противном случае ни один из заказов в запросе не будет создан.
        "method" => "self_delivery", // Метод отгрузки self_delivery - самопривоз, или pickup - забор.
        "punkt_id" => "AB-SPB-Moskovskaya", // Пункт отгрузки, если метод отгрузки self_delivery
        "pickup_id" => 123, // Номер заявки на забор, если метод отгрузки pickup

        // Следующие параметры передавайте, только если нужно создать новый забор (т.е. нужен забор, но у вас еще нет pickup_id)
        "pickup_city" => "7800000000000", // Кладр города (или "SPB" или "Санкт-Петербург").
        "pickup_date" => "2020-02-03", // Дата забора в формате "Y-m-d". Должна быть не раньше завтрашнего дня
        "pickup_interval" => "10-18", // Интервал забора
        "pickup_address" => "Санкт-Петербург, ул Седова д.12",
        "pickup_comment" => "Комментарий к заявке на забор" // Не обязательно
    ),
    "orders" => array(
        // Заказ на выдачу в ПВЗ
        array(
            "serv" => "выдача",
            "pvz_id" => "AB-SPB-Moskovskaya",
            "sku" => "ТEST-PKG-1",
            "price" => 1000, // Сумма к получению. Если передан 0, значит заказ предоплачен.
            "insurance_val" => 2000, // Оценочная (страховая) стоимость заказа
            "weight" => 0.3, // Общий вес заказа в кг.
            "parts" => array( // Номенклатура заказа
                array(
                    "name" => "Футболка 1",
                    "price" => 200, // Сумма к получению за единицу товара
                    "insurance_val" => 400, // Оценочная (страховая) стоимость единицы товара
                    "num" => 1, // Количество позиций товара (по-умолчанию 1)
                    "weight" => 0.1
                ),
                array(
                    "name" => "Футболка 2",
                    "price" => 200,
                    "insurance_val" => 400,
                    "num" => 1,
                    "weight" => 0.1
                ),
                array(
                    "name" => "Футболка 3",
                    "price" => 600,
                    "insurance_val" => 1200,
                    "num" => 1,
                    "weight" => 0.1
                )
            )
        ),
        // Заказ на доставку Почтой России
        array(
            "serv" => "почта",
            "sku" => "ТEST-PKG-3",
            "price" => 1000,
            "insurance_val" => 2000,
            "buyer_phone" => "79001112233",
            "weight" => 1,
            "pochta" => array(
                "address" => "123098, Россия, москва Рогова, дом 12, корпус 2, строение 1",
                "index" => "123098"
            ),
            "parts" => array(
                array(
                    "name" => "Футболка",
                    "price" => 1000,
                    "insurance_val" => 2000
                )
            )
        ),
        // Заказ на курьерскую доставку
        array(
            "serv" => "курьерская доставка",
            "sku" => "ТEST-PKG-2",
            "barcode" => "830467",
            "price" => 0, // Заказ предоплачен
            "insurance_val" => 2000,
            "buyer_phone" => "79001112233",
            "buyer_fio" => "Иванов И.И",
            "buyer_email" => "email@mailserver.com",
            "weight" => 1,
            "delivery" => array( // Параметры курьерской доставки
                "city" => "7800000000000", // Кладр города (или "SPB" или "Санкт-Петербург").
                "address" => "ул. Маяковского д.9",
                "date" => "2025-02-03",
                "time_from" => "10:00",
                "time_to" => "18:00"
            ),
            "parts" => array(
                array(
                    "name" => "Футболка",
                    "price" => 0,
                    "insurance_val" => 2000
                )
            )
        ),
        // Заказ с возможностью частичной выдачи
        array(
            "serv" => "выдача",
            "pvz_id" => "AB-SPB-Moskovskaya",
            "partial_giveout_enabled" => 1, // признак возможности частичной выдачи
            "sku" => "ТEST-PKG-1",
            "price" => 500, // Для заказов с возможностью частичной выдачи, поле price должно
            // совпадать с суммой полей price в номенклатуре (parts)
            "insurance_val" => 200,
            "parts" => array( // Номенклатура заказа
                array(
                    "name" => "Футболка",
                    "price" => 200,
                    "insurance_val" => 200
                ),
                array(
                    // Обратите внимание: в случае, если нужно взять с клиента стоимость доставки,
                    // ее требуется передать отдельной частью.
                    "name" => "Стоимость доставки",
                    "price" => 300,
                    "insurance_val" => 0
                )
            )
        ),
    )
);

$gpApi->createShipment($data);
   ```
2.Получение списка пунктов выдачи:
   ```php
     use Glavpunkt\GlavpunktApi;
     $gpApi = new GlavpunktApi(LOGIN,TOKEN);
     $gpApi->punkts();
   ```
Для получения более подробной информации
<a href='http://glavpunkt.ru/apidoc/php.html#php' target='_blank'>перейдите по ссылке</a>
