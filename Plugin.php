<?php namespace Waka\Wakajob;

use Backend;
use Backend\Classes\Controller;
use Cms\Classes\ComponentBase;
use Event;
use Flash;
use Lang;
use Illuminate\Foundation\AliasLoader;
use Waka\Wakajob\Classes\BackendInjector;
use Waka\Wakajob\Classes\DependencyInjector;
use Waka\Wakajob\Classes\RouteResolver;
use Waka\Wakajob\Console\Optimize;
use Waka\Wakajob\Console\QueueClearCommand;
use Waka\Wakajob\Classes\LaravelQueueClearServiceProvider;
use System\Classes\PluginBase;
use Waka\LaravelWakajob\LaravelWakajobServiceProvider;
use Winter\Storm\Translation\Translator;
//use Waka\Wakajob\FormWidgets\KnobWidget;
use \Waka\Utils\Models\Settings as UtilsSettings;

/**
 * Wakajob Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Wakajob',
            'description' => 'waka.wakajob::lang.labels.pluginName',
            'author'      => 'Waka',
            'icon'        => 'icon-cogs',
        ];
    }


    /**
     * @return array
     */
    public function registerPermissions(): array
    {
        return [
            'waka.wakajob.admin.base' => [
                'tab'   => 'Waka - Jobs',
                'label' => 'Administrateur de wakaJob',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'notification' => [
                'label' => Lang::get('waka.wakajob::lang.menu.job_list'),
                'description' => Lang::get('waka.wakajob::lang.menu.job_list_description'),
                'category' => Lang::get('waka.wutils::lang.menu.model_tasks'),
                'icon' => 'icon-refresh',
                'url' => Backend::url('waka/wakajob/jobs'),
                'order' => 001,
                'permissions' => ['waka.wakajob.admin.*'],
                'counterLabel' => Lang::get('waka.wakajob::lang.joblist.btn_counter_label'),
            ]
        ];
    }

    /**
     * Plugin register method
     */
    public function register(): void
    {
        $this->app->register(LaravelQueueClearServiceProvider::class);
        $this->commands(
            [
                Optimize::class,
                QueueClearCommand::class,
            ]
        );
    }
}
