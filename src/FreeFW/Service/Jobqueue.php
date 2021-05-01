<?php
namespace FreeFW\Service;

/**
 * Jobqueue
 *
 * @author jeromeklam
 */
class Jobqueue extends \FreeFW\Core\Service
{

    /**
     * Handle waiting and retry jobs
     */
    protected function handleStandard()
    {
        /**
         * @var \FreeFW\Core\StorageModel $jobqueue
         */
        $jobqueue = \FreeFW\DI\DI::get('FreeFW::Model::Jobqueue');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = $jobqueue->getQuery();
        $query->addFromFilters(
            [
                'jobq_status' => [
                    \FreeFW\Storage\Storage::COND_IN => [
                        \FreeFW\Model\Jobqueue::STATUS_WAITING,
                        \FreeFW\Model\Jobqueue::STATUS_RETRY
                    ]
                ],
                'jobq_next_retry' => [
                    \FreeFW\Storage\Storage::COND_LOWER_EQUAL_OR_NULL => \FreeFW\Tools\Date::getCurrentTimestamp()
                ]
            ]
        );
        if ($query->execute()) {
            $results = $query->getResult();
            foreach ($results as $jobqueue) {
                $jobqueue->addToHistory();
                $jobqueue->run();
            }
        }
    }

    /**
     * Handle waiting and retry jobs
     */
    protected function handleDead()
    {
        /**
         * @var \FreeFW\Core\StorageModel $jobqueue
         */
        $jobqueue = \FreeFW\DI\DI::get('FreeFW::Model::Jobqueue');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = $jobqueue->getQuery();
        $query->addFromFilters(
            [
                'jobq_status' => \FreeFW\Model\Jobqueue::STATUS_PENDING,
                'jobq_last_ts' => [
                    \FreeFW\Storage\Storage::COND_LOWER_EQUAL_OR_NULL => \FreeFW\Tools\Date::getCurrentTimestamp(-1440)
                ]
            ]
            );
        if ($query->execute()) {
            $results = $query->getResult();
            foreach ($results as $jobqueue) {
                $jobqueue->reset();
            }
        }
    }

    /**
     * Verify jobqueue
     *
     * @return boolean
     */
    public function handle()
    {
        $this->handleStandard();
        $this->handleDead();
        return true;
    }
}
