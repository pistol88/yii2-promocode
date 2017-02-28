Yii2-promocode
==========
Добавление функционала промокодов (купонов) на сайт, работает с [pistol88/cart](http://github.com/pistol88/yii2-cart).

Привязка промокода к модели.

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

В targetModelList указать модели для привязки промокода

```php
    'modules' => [
        //..
        'promocode' => [
            'class' => 'pistol88\promocode\Module',
            'usesModel' => 'dektrium\user\models\User', //Модель пользователей
            //Указываем модели, к которым будем привязывать промокод
            'targetModelList' => [
                'Категории' => [
                    'model' => 'pistol88\service\models\Category',
                    'searchModel' => 'pistol88\service\models\category\CategorySearch'
                ],
                'Продукты' => [
                    'model' => 'pistol88\shop\models\Product',
                    'searchModel' => 'pistol88\shop\models\product\ProductSearch'
                ],            
            ],
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

Чтобы скидка применялась для отдельных моделей необходимо добавить поведение pistol88\promocode\behaviors\DiscountToElement для cart при подключении компонента в конфиге:

```php
    'cart' => [
        'class' => 'pistol88\cart\Cart',
        //'as PromoDiscount' => ['class' => 'pistol88\promocode\behaviors\Discount'],
        'as ElementDiscount' => ['class' => 'pistol88\promocode\behaviors\DiscountToElement'],
    ]
```

Виджеты
---------------------------------
Вывод формы ввода промокода для пользователя:
<?=\pistol88\promocode\widgets\Enter::widget();?>

Целую, пока!
