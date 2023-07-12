<?php
/**
 * Copyright (c) 2018 Viamage Limited
 * All Rights Reserved
 *
 *  NOTICE:  All information contained herein is, and remains
 *  the property of Viamage Limited and its suppliers, if any.
 *  The intellectual and technical concepts contained herein
 *  are proprietary to Viamage Limited and its suppliers and are
 *  protected by trade secret or copyright law, if not specified otherwise.
 *  Dissemination of this information or reproduction of this material
 *  is strictly forbidden unless prior written permission is obtained
 *  from Viamage Limited.
 *
 */

/**
 * Created by PhpStorm.
 * User: jin
 * Date: 4/15/18
 * Time: 4:43 PM
 */

namespace Waka\Wakajob\Jobs;

use Waka\Wakajob\Classes\JobManager;
use Waka\Wakajob\Classes\RequestSender;
use Waka\Wakajob\Contracts\WakajobQueueJob;
use Winter\Storm\Database\Model;
use Viamage\CallbackManager\Models\Rate;

/**
 * Class SendRequestJob
 *
 * Sends POST requests with given data to multiple target urls. Example of Wakajob Job.
 *
 * @package Waka\Wakajob\Jobs
 */
class CreatePdf implements WakajobQueueJob
{
    /**
     * @var int
     */
    public $jobId;

    /**
     * @var JobManager
     */
    public $jobManager;

    /**
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $updateExisting;

    /**
     * @var int
     */
    private $chunk;

    /**
     * @var string
     */
    private $table;

    /**
     * @param int $id
     */
    public function assignJobId(int $id)
    {
        $this->jobId = $id;
    }

    /**
     * SendRequestJob constructor.
     *
     * We provide array with stuff to send with post and array of urls to which we want to send
     *
     * @param array  $data
     * @param string $model
     * @param bool   $updateExisting
     * @param int    $chunk
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->updateExisting = true;
        $this->chunk = 1;
        /** @var Model $model */
        //$model = new $model();
        //$this->table = $model->getTable();
    }

    /**
     * Job handler. This will be done in background.
     *
     * @param JobManager $jobManager
     */
    public function handle(JobManager $jobManager)
    {
        /**
         * We initialize database job. It has been assigned ID on dispatching,
         * so we pass it together with number of all elements to proceed (max_progress)
         */
        $loop = 1;
        $jobManager->startJob($this->jobId, \count($this->data));
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $data = array_chunk($this->data, $this->chunk);
        try {
            foreach ($data as $chunk) {
                foreach ($chunk as $data) {
                    if ($jobManager->checkIfCanceled($this->jobId)) {
                        $jobManager->failJob($this->jobId);
                        break;
                    }
                    //trace_log("I am creating a pdf man ! ");
                    if ($this->updateExisting) {
                        // TODO !
                    }
                    if (!$entry) {
                        ++$created;
                    } else {
                        if ($this->updateExisting) {
                            ++$updated;
                        } else {
                            ++$skipped;
                            continue;
                        }
                    }
                    //$toInsert[] = $data;
                }
                //\DB::table($this->table)->insert($toInsert);
                $loop += $this->chunk;
                $jobManager->updateJobState($this->jobId, $loop);
            }
        } catch (\Exception $ex) {
            $jobManager->failJob($this->jobId, ['error' => $ex->getMessage()]);
        }
        $jobManager->completeJob(
            $this->jobId,
            [
                'message' => \count($this->data).' Fichiers crées',
                'Created' => $created,
                'Updated' => $updated,
                'Skipped' => $skipped,
            ]
        );
    }
}
