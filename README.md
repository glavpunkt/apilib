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



Получение списка пунктов выдачи:
   ```php
     use Glavpunkt\GlavpunktApi;
     $gpApi = new GlavpunktApi(LOGIN,TOKEN);
     $gpApi->punkts();
   ```

Для получения более подробной инфорации
<a href='http://glavpunkt.ru/apidoc/php.html#php' target='_blank'>перейдите по ссылке</a>
