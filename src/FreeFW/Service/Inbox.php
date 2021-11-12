<?php
namespace FreeFW\Service;

/**
 *
 * @author jeromeklam
 *
 */
class Inbox extends \FreeFW\Core\Service
{
    /**
     * Clean / check Inbox
     *
     * @return void
     */
    public function clean()
    {
        $Inboxs = \FreeFW\Model\Inbox::find();
        /**
         * @var \FreeFW\Model\Inbox $oneInbox
         */
        foreach ($Inboxs as $oneInbox) {
            $save   = false;
            $delete = false;
            if ($oneInbox->getInboxDownloadTs()) {
                $notifDate = \FreeFW\Tools\Date::mysqlToDatetime($oneInbox->getInboxDownloadTs());
                $nbDays    = \FreeFW\Tools\Date::nbDaysAtNow($notifDate);
                if ($nbDays < -45) {
                    $delete = true;
                }
            }
            if ($save) {
                // Raw save
                $oneInbox->save(true);
            } else {
                if ($delete) {
                    $oneInbox->remove(true);
                }
            }
        }
    }
}
