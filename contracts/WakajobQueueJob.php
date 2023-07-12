<?php
/**
 * Created by PhpStorm.
 * User: jin
 * Date: 1/3/18
 * Time: 10:17 AM
 */

namespace Waka\Wakajob\Contracts;

interface WakajobQueueJob
{
    public function assignJobId(int $id);
}
