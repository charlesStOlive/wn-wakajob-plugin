<?php namespace Waka\Wakajob\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use Illuminate\Http\RedirectResponse;
use Waka\Wakajob\Contracts\JobStatus;
use Lang;
use Waka\Wakajob\Models\Job;
use Redirect;
use Request;

/**
 * Jobs Back-end Controller
 */
class Jobs extends Controller
{
    /**
     * @var array
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    /**
     * @var string
     */
    public $formConfig = 'config_form.yaml';
    /**
     * @var string
     */
    public $listConfig = 'config_list.yaml';

    /**
     * Jobs constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Waka.Wakajob', 'wakajob', 'jobs');
    }

    /**
     * Index method
     */
    public function index(): void
    {
        $this->addJs('/plugins/waka/wakajob/assets/js/wakajob-jobs.js');

        $this->asExtension('ListController')->index();
    }

    /**
     * @param int $id
     */
    public function view(int $id): void
    {
        /**
         * @var Job|null $job
         */
        $job = Job::where('id', $id)->first();

        if (null === $job) {
            $this->pageTitle = 'Job not found.';
            $this->fatalError = sprintf('Job with id .'.$id.' does not exist.');
        } else {
            $this->addJs('/plugins/waka/wakajob/assets/js/wakajob-jobs.js');
            $this->pageTitle = 'Job '.$id.' - '.Lang::get($job->label);
            $this->vars['job'] = $job;
        }
    }

    /**
     * @return bool
     */
    public function isSuperuser(): bool
    {
        return (bool)\BackendAuth::getUser()->is_superuser;
    }

    /**
     * Deleted checked jobs.
     */
    public function index_onDelete(): array
    {
        if (($checkedIds = post('checked')) && \is_array($checkedIds) && \count($checkedIds)) {
            foreach ($checkedIds as $jobId) {
                if (!$job = Job::find($jobId)) {
                    continue;
                }
                $job->delete();
            }

            Flash::success(Lang::get('waka.wakajob::lang.jobs.delete_selected_success'));
        } else {
            Flash::error(Lang::get('waka.wakajob::lang.jobs.delete_selected_empty'));
        }

        return $this->listRefresh();
    }

    /**
     * @return array
     */
    public function onGetProgress(): array
    {
        $ids = post('ids');
        $jobs = Job::whereIn('id', $ids)
            ->select(['id', 'status', 'progress', 'progress_max'])
            ->get()
            ->map(
                function (Job $job) {
                    $stdClass = (object)$job->attributes;
                    $stdClass->statusCode = $job->status;
                    $stdClass->status = $job->getStatus();
                    $stdClass->percent = $job->progressPercent();

                    return $stdClass;
                }
            )
            ->toArray();
            //trace_log($jobs);
        return [
            'jobs' => $jobs,
        ];
    }

    /**
     *
     */
    public function onCancelJob(): void
    {
        $id = post('id');

        /**
         * @var Job $job
         */
        $job = Job::where('id', $id)->firstOrFail();

        if ($job->canBeCanceled()) {
            $job->is_canceled = true;
            $job->save();

            Flash::success(sprintf('Cancelling job %s - %s...', $job->getKey(), $job->type));
        } else {
            Flash::error(sprintf('Could not cancel job %s - %s.', $job->getKey(), $job->type));
        }
    }

    /**
     * @return RedirectResponse
     */
    public function onForceCancelJob(): RedirectResponse
    {
        $id = post('id');

        /**
         * @var Job $job
         */
        $job = Job::where('id', $id)->firstOrFail();
        $job->is_canceled = true;
        $job->status = JobStatus::STOPPED;
        $job->save();

        Flash::success(sprintf('Force canceled job %s - %s.', $job->getKey(), $job->type));

        return Redirect::to(Request::url());
    }

    public function onTest()
    {
        $data = [];
        for ($i=0; $i<10; $i++) {
            $data[$i] = $i;
        }
        $job = new \Waka\Wakajob\Jobs\TestJob($data);
        $jobManager = \App::make('Waka\Wakajob\Classes\JobManager');
        $jobManager->dispatch($job, 'Requests sending');
        return \Redirect::to(\Backend::url('waka/wakajob/jobs/view/' . $job->jobId));
    }

    public function index_onRefresh()
    {
        return $this->listRefresh();
    }
    public function onViewRefresh()
    {
        return \Redirect::refresh();
    }
}
