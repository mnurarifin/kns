<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */


    public static function Captcha($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Captcha');
        }

        return new \App\Libraries\Captcha();
    }

    public static function Bonus($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Bonus');
        }

        return new \App\Services\Bonus_services;
    }

    public static function Cron($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Cron');
        }

        return new \App\Services\Cron_services;
    }

    public static function Ewallet($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Ewallet');
        }

        return new \App\Services\Ewallet_services;
    }

    public static function Membership($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Membership');
        }

        return new \App\Services\Membership_services;
    }

    public static function Mlm($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Mlm');
        }

        return new \App\Services\Mlm_services;
    }

    public static function Netgrow($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Netgrow');
        }

        return new \App\Services\Netgrow_services;
    }

    public static function Network($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Network');
        }

        return new \App\Services\Network_services;
    }

    public static function Profitsharing($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Profitsharing');
        }

        return new \App\Services\Profitsharing_services;
    }

    public static function Rank($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Rank');
        }

        return new \App\Services\Rank_services;
    }

    public static function Royalty($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Royalty');
        }

        return new \App\Services\Royalty_services;
    }

    public static function Registration($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Registration');
        }

        return new \App\Services\Registration_services;
    }

    public static function Repeatorder($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Repeatorder');
        }

        return new \App\Services\Repeatorder_services;
    }

    public static function Report($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Report');
        }

        return new \App\Services\Report_services;
    }

    public static function Reward($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Reward');
        }

        return new \App\Services\Reward_services;
    }

    public static function Serial($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Serial');
        }

        return new \App\Services\Serial_services;
    }

    public static function Tax($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Tax');
        }

        return new \App\Services\Tax_services;
    }

    public static function Template($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Template');
        }

        return new \App\Services\Template_services;
    }

    public static function Transaction($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Transaction');
        }

        return new \App\Services\Transaction_services;
    }

    public static function Upgrade($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Upgrade');
        }

        return new \App\Services\Upgrade_services;
    }

    public static function Stock($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Stock');
        }

        return new \App\Services\Stock_services;
    }

    public static function DataTableLib($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('DataTableLib');
        }

        return new \App\Libraries\DataTable();
    }

    public static function FunctionLib($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('FunctionLib');
        }

        return new \App\Libraries\FunctionLib();
    }

    public static function Activation($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Activation');
        }

        return new \App\Services\Activation_services;
    }

    public static function Payment($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('Payment');
        }

        return new \App\Services\Payment_services;
    }
}
