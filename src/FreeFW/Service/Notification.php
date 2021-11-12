<?php
namespace FreeFW\Service;

/**
 *
 * @author jeromeklam
 *
 */
class Notification extends \FreeFW\Core\Service
{
    /**
     * Clean / check notifications
     *
     * @return void
     */
    public function clean()
    {
        $notifications = \FreeFW\Model\Notification::find();
        /**
         * @var \FreeFW\Model\Notification $oneNotif
         */
        foreach ($notifications as $oneNotif) {
            $save   = false;
            $delete = false;
            if ($oneNotif->getNotifRead()) {
                $notifDate = \FreeFW\Tools\Date::mysqlToDatetime($oneNotif->getNotifTs());
                $nbDays    = \FreeFW\Tools\Date::nbDaysAtNow($notifDate);
                if ($nbDays < -365) {
                    $delete = true;
                }
            } else {
                if ($oneNotif->getNotifType() == \FreeFW\Model\Notification::TYPE_INFORMATION ||
                    $oneNotif->getNotifType() == \FreeFW\Model\Notification::TYPE_OTHER) {
                    $notifDate = \FreeFW\Tools\Date::mysqlToDatetime($oneNotif->getNotifTs());
                    $nbDays    = \FreeFW\Tools\Date::nbDaysAtNow($notifDate);
                    if ($nbDays < -2) {
                        $oneNotif
                            ->setNotifRead(1)
                            ->setNotifReadTs(\FreeFW\Tools\Date::getCurrentTimestamp())
                        ;
                        $save = true;
                    }
                }
            }
            if ($save) {
                // Raw save
                $oneNotif->save(true);
            } else {
                if ($delete) {
                    $oneNotif->remove(true);
                }
            }
        }
    }
}
