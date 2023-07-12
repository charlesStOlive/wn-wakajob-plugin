<?php
/**
 * Created by Waka Solutions
 * User: Jakub Zych
 * Date: 5/31/16
 * Time: 9:42 PM
 */

namespace Waka\Wakajob\Contracts;

/**
 * Interface JobStatus
 * @package Waka\Wakajob\Contracts
 */
interface JobStatus
{
    const IN_QUEUE = 0;
    const IN_PROGRESS = 1;
    const COMPLETE = 2;
    const ERROR = 3;
    const STOPPED = 4;
}
