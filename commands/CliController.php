<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Currency;
use app\models\Transaction;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CliController extends Controller
{
    /**
     * @return int
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionGetCurrencyRates()
    {
        return Currency::dailyRates() ? ExitCode::OK : ExitCode::UNSPECIFIED_ERROR;
    }

    public function actionHydroTransactions()
    {
        (new Transaction(['to' => 1, 'value' => 1500]))->save();
        (new Transaction(['from' => 1, 'to' => 2, 'value' => 5.5]))->save();
        (new Transaction(['from' => 1, 'to' => 3, 'value' => 6.45]))->save();
        (new Transaction(['from' => 2, 'to' => 3, 'value' => 3]))->save();
    }
}
