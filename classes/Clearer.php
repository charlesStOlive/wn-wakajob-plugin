<?php namespace Waka\Wakajob\Classes;

use Illuminate\Queue\QueueManager;
use Illuminate\Contracts\Queue\Factory as FactoryContract;
use Waka\Wakajob\Contracts\Clearer as ClearerContract;

class Clearer implements ClearerContract
{
    /**
     * @var QueueManager
     */
    protected $manager;

    /**
     * {@inheritDoc}
     */
    public function __construct(FactoryContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function clear($connection, $queue)
    {
        $count = 0;
        $connection = $this->manager->connection($connection);
        while ($job = $connection->pop($queue)) {
            $job->delete();
            $count++;
        }

        return $count;
    }
}
