Yii2-promocode
==========
Добавление функционала промокодов (купонов) на сайт, работает с [pistol88/cart](http://github.com/pistol88/yii2-cart).

Установка
---------------------------------
Выполнить команду

```
php composer require pistol88/yii2-promocode "*"
```

Или добавить в composer.json

```
"pistol88/yii2-promocode": "*",
```

И выполнить

```
php composer update
```

Миграция:

php yii migrate --migrationPath=vendor/pistol88/yii2-promocode/migrations

Подключение и настройка
---------------------------------
В конфигурационный файл приложения добавить модуль promocode

```php
    'modules' => [
        //..
        'promocode' => [
            'class' => 'pistol88\promocode\Module',
        ],
        //..
    ]
```

Использование
---------------------------------

Чтобы управлять промокодами, нужно перейти к контроллеру модуля: ?r=promocode/promo-code

Добавление промокода для текущего пользователя:
```php
yii::$app->promocode->enter($promocode);
```

Очистить текущий промокод пользователя:
```php
yii::$app->promocode->clear()
```

Проверить, введен ли промокод:
```php
if(yii::$app->promocode->has())
```

Получить текущий промокод:
```php
yii::$app->promocode->getCode()
```

Получить процент скидки текущий:
```php
$persent = yii::$app->promocode->get()->promocode->discount;
```

Чтобы скидка применялась для [pistol88/cart](http://github.com/pistol88/yii2-cart), необходимо добавить поведение pistol88\promocode\behaviors\Discount для cart при подключении cart в конфиге:

```php
    'cart' => [
        'class' => 'pistol88\cart\Cart',
        'as PromoDiscount' => ['class' => 'pistol88\promocode\behaviors\Discount'],
    ]
```


Виджеты
---------------------------------
Вывод формы ввода промокода для пользователя:
<?=\pistol88\promocode\widgets\Enter::widget();?>

Целую, пока!