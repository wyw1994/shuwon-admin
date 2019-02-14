<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');//dev=>开发版;stage=>测试版;pro=>生产环境;

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../helpers/helpers.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../common/config/bootstrap.php');
require(__DIR__ . '/../backend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../common/config/main-local.php'),
    require(__DIR__ . '/../backend/config/main-local.php'),
    require(__DIR__ . '/../common/config/main.php'),
    require(__DIR__ . '/../backend/config/main.php')
);
$application = new yii\web\Application($config);
$application->run();
