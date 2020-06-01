<?php
if (is_file(__DIR__ . '/.env')) {
    (new \Dotenv\Dotenv(__DIR__))->load();
}

defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV'));

class Yii extends \yii\BaseYii
{
    /**
     * @var \install\Application|BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;

    public static function getVersion()
    {
        return '1.0.0';
    }
    public static function powered()
    {
        return parent::powered() . ' & <a href="http://www.jihexian.com/" rel="external">yjshop</a>';
    }

}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(__DIR__ . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container();


function checkInstalled()
{
    return file_exists(Yii::getAlias('@root/web/storage/install.txt'));
}


//IDE友好

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 * @property \common\modules\config\components\Config $config
 * @property \common\modules\attachment\components\FilesystemManager|\common\modules\attachment\components\FilesystemAdapter $storage
 * @property \common\components\notify\Handler $notify
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Class WebApplication
 * Include only Web application related components here
 * @property \frontend\components\Search $search
 * @property \common\components\PluginManager $pluginManager
 * @property \common\components\ModuleManager $moduleManager
 * @property \common\components\ThemeManager $themeManager
 * @property User $user
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 *
 */
class ConsoleApplication extends yii\console\Application
{
}

/**
 * Class User
 * @property \common\modules\user\models\User $identity
 */
class User
{
}