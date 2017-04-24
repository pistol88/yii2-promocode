Внимание!
==========
Разработка модуля с 24.04.2017 ведется здесь: [dvizh/yii2-promocode](https://github.com/dvizh/yii2-promocode). Рекомендую устанавливать модуль из репозиторий Dvizh, именно там находится последняя версия.

Yii2-promocode
==========
Добавление функционала скидок (промокодов, купонов) на сайт, стабильно работает с [pistol88/cart](http://github.com/pistol88/yii2-cart).

Модуль умеет через Behavior динамически менять цену заказа, исходя из вида примененного купона: накопительный, "процент скидки", "сумма скидки".

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
            'informer' => 'pistol88\cart\widgets\CartInformer', // namespace to custom cartInformer widget
            'informerSettings' => [], //settings for custom cartInformer widget
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
