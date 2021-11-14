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
     * Deferred email
     *
     * @param mixed $p_params
     * @param mixed $p_user_id
     *
     * @return boolean
     */
    public function deferredEmail($p_params, $p_user_id = null)
    {
        $this->logger->debug('FreeFW.Service.Jobqueue.deferredEmail.start');
        $result = true;
        /**
         * @var \FreeFW\Model\Query $query
         */
        $model   = \FreeFW\DI\DI::get($p_params['model']);
        $query   = $model->getQuery();
        $params  = unserialize($p_params['api']);
        $emailId = $p_params['email_id'];
        /**
         *
         * @var \FreeFW\Model\Conditions $conditions
         */
        $conditions = $params->getFilters();
        $query
            ->addConditions($params->getFilters())
            ->addRelations($params->getInclude())
            ->setLimit($params->getStart(), $params->getlength())
            ->setSort($params->getSort())
        ;
        $email = \FreeFW\Model\Email::findFirst(['email_id' => $emailId]);
        if ($email) {
            if (method_exists($model, 'sendEmail')) {
                $query->execute([], 'sendEmail', [$email]);
            } else {
                $this->logger->debug('FreeFW.Service.Jobqueue.deferredEmail.sendEmail.notfound');
                return false;
            }
        } else {
            return false;
        }
        // Add notification and inbox
        $object = str_replace('::Model::', '_', $p_params['model']);
        $parts  = explode('_', $object);
        $date   = \str_replace('-', '', \FreeFW\Tools\Date::getCurrentDate());
        $name   = array_pop($parts) . '_' . $date;
        $notification = new \FreeFW\Model\Notification();
        $notification
            ->setNotifCode('EMAIL')
            ->setNotifType(\FreeFW\Model\Notification::TYPE_INFORMATION)
            ->setNotifSubject('Envoi d\'email terminé')
            ->setNotifObjectName($object)
            ->setUserId($p_user_id)
        ;
        if (!$notification->create()) {
            $result = false;
        }
        // data can be empty, but it's a 2*
        $this->logger->debug('FreeFW.Service.Jobqueue.deferredEmail.end');
        return $result;
    }

    /**
     * Deferred export
     *
     * @param mixed $p_params
     * @param mixed $p_user_id
     *
     * @return boolean
     */
    public function deferredExport($p_params, $p_user_id = null)
    {
        $this->logger->debug('FreeFW.Service.Jobqueue.deferredExport.start');
        $result = true;
        /**
         * @var \FreeFW\Model\Query $query
         */
        $model = \FreeFW\DI\DI::get($p_params['model']);
        $query = $model->getQuery();
        $params = unserialize($p_params['api']);
        /**
         *
         * @var \FreeFW\Model\Conditions $conditions
         */
        $conditions = $params->getFilters();
        $query
            ->addConditions($params->getFilters())
            ->addRelations($params->getInclude())
            ->setLimit($params->getStart(), $params->getlength())
            ->setSort($params->getSort())
        ;
        $tmpFile = '/tmp/export_' . uniqid() . '.xlsx';
        $sheet = new \FreeOffice\Model\SpreadSheet($tmpFile);
        try {
            $query->execute([], 'exportAsSheet', [$sheet, $params->getInclude()]);
        } catch (\Exception $ex) {
            $myEx = \FreeFW\Tools\Exception::format($ex); 
            $this->logger->error($myEx);
            $sheet->close();
            @unlink($tmpFile);
            return false;
        }
        $sheet->close();
        // Add notification and inbox
        $object = str_replace('::Model::', '_', $p_params['model']);
        $parts  = explode('_', $object);
        $date   = \str_replace('-', '', \FreeFW\Tools\Date::getCurrentDate());
        $name   = array_pop($parts) . '_' . $date;
        $inbox = new \FreeFW\Model\Inbox();
        $inbox
            ->setInboxFilename($name . '.xlsx')
            ->setInboxObjectName($object)
            ->setInboxParams(json_encode($p_params))
            ->setInboxContent(file_get_contents($tmpFile))
            ->setUserId($p_user_id)
        ;
        if (!$inbox->create()) {
            $result = false;
        }
        $notification = new \FreeFW\Model\Notification();
        $notification
            ->setNotifCode('EXPORT')
            ->setNotifType(\FreeFW\Model\Notification::TYPE_INFORMATION)
            ->setNotifSubject('Export terminé')
            ->setNotifObjectName($object)
            ->setUserId($p_user_id)
        ;
        if (!$notification->create()) {
            $result = false;
        }
        // data can be empty, but it's a 2*
        $this->logger->debug('FreeFW.Service.Jobqueue.deferredExport.end');
        return $result;
    }

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
            /**
             * @var \FreeFW\Model\Jobqueue $jobqueue
             */
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
                'jobq_status' => \FreeFW\Model\Jobqueue::STATUS_PENDING
            ]
        );
        if ($query->execute()) {
            /**
             * @var \FreeFW\Service\Message msgService
             */
            $msgService = \FreeFW\DI\DI::get('FreeFW::Service::Message');
            $results = $query->getResult();
            /**
             * @var \FreeFW\Model\Jobqueue $jobqueue
             */
            foreach ($results as $jobqueue) {
                $fromDate  = \FreeFW\Tools\Date::mysqlToDatetime($jobqueue->getJobqLastTs());
                $fromHours = abs(\FreeFW\Tools\Date::nbHoursAtNow($fromDate));
                if ($fromHours > $jobqueue->getJobqMaxHour()) {
                    $msgService->sendAdminMessage('JobQueue pending reset', print_r($jobqueue, true));
                    $jobqueue->reset();
                }
            }
        }
    }

    /**
     * Remove old jobs
     */
    protected function handleOld()
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
                'jobq_status' => \FreeFW\Model\Jobqueue::STATUS_FINISHED,
                'jobq_type' => \FreeFW\Model\Jobqueue::TYPE_ONCE,
                'jobq_last_ts' => [
                    \FreeFW\Storage\Storage::COND_LOWER_EQUAL_OR_NULL => \FreeFW\Tools\Date::getCurrentTimestamp(-1440)
                ]
            ]
        );
        if ($query->execute()) {
            $results = $query->getResult();
            foreach ($results as $jobqueue) {
                $jobqueue->remove();
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
        $this->handleOld();
        return true;
    }
}
