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
use Waka\Wakajob\FormWidgets\ListToggle;
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
    public function registerComponents(): array
    {
        return [
            //Components\Messaging::class => 'wakajobFlashMessages',
        ];
    }

    /**
     * @return array
     */
    public function registerPermissions(): array
    {
        return [
            'waka.wakajob.user' => [
                'tab'   => 'waka.wakajob::lang.permissions.tab',
                'label' => 'waka.wakajob::lang.permissions.access_settings',
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerNavigation(): array
    {
        return [];
    }

    public function registerSettings()
    {
        return [
            'notification' => [
                'label' => Lang::get('waka.utils::lang.menu.job_list'),
                'description' => Lang::get('waka.utils::lang.menu.job_list_s'),
                'category' => Lang::get('waka.utils::lang.menu.settings_controle'),
                'icon' => 'icon-refresh',
                'url' => Backend::url('waka/wakajob/jobs'),
                'order' => 1,
                'permissions' => ['waka.wakajob.*'],
                'counterLabel' => Lang::get('waka.utils::lang.joblist.btn_counter_label'),
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

    /**
     * @return array
     */
    public function registerListColumnTypes(): array
    {
        return [
            'listtoggle' => [ListToggle::class, 'render'],
        ];
    }


    /**
     * Plugin boot method
     * @throws \ApplicationException
     */
    public function boot(): void
    {
        /**
         * POur le bouton des jobs
         */
        // Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
        //     $user = \BackendAuth::getUser();
        //     if ($user->hasAccess('waka.jobList.*') && UtilsSettings::get('activate_task_btn')) {
        //         // $pluginUrl = url('/plugins/waka/wakajob');
        //         // \Block::append('body', '<script type="text/javascript" src="' . $pluginUrl . '/assets/js/backendnotifications.js"></script>');
        //     }
        // });
    }
}
