<?php
namespace FreeFW\Service;

/**
 * Email service
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Core\Service
{

    /**
     * Get new message from email
     *
     * @param array                     $p_filters
     * @param number                    $p_lang_id
     * @param \FreeFW\Core\StorageModel $p_model
     * @param boolean                   $p_merge
     *
     * @return NULL|\FreeFW\Model\Message
     */
    public function getEmailAsMessage(array $p_filters, int $p_lang_id, \FreeFW\Core\StorageModel $p_model, $p_merge = true)
    {
        $message = null;
        $emails  = \FreeFW\Model\Email::find($p_filters);
        /**
         * @var \FreeFW\Model\Email $oneEmail
         */
        foreach ($emails as $oneEmail) {
            $emailVersion = null;
            foreach ($oneEmail->getVersions() as $oneVersion) {
                if ($oneVersion->getLangId() == $p_lang_id) {
                    $emailVersion = $oneVersion;
                    break;
                }
            }
            if ($emailVersion === null) {
                foreach ($oneEmail->getVersions() as $oneVersion) {
                    $emailVersion = $oneVersion;
                    if ($oneVersion->getLangId() == $oneEmail->getLangId()) {
                        break;
                    }
                }
            }
            if ($emailVersion !== null) {
                $datas  = $p_model->getMergeData();
                $fields = $datas->__toArray();
                //
                $message = new \FreeFW\Model\Message();
                $subject = $oneVersion->getEmaillSubject();
                $body    = $oneVersion->getEmaillBody();
                if ($p_merge) {
                    $subject = \FreeFW\Tools\PBXString::parse($subject, $fields);
                    $body    = \FreeFW\Tools\PBXString::parse($body, $fields);
                    if ($oneEmail->getEmailEdi1Id()) {
                        try {
                            $editionService = \FreeFW\DI\DI::get('FreeFW::Service::Edition');
                            $datas = $editionService->printEdition(
                                $oneEmail->getEmailEdi1Id(),
                                $p_lang_id,
                                $p_model
                            );
                            if (isset($datas['filename']) && is_file($datas['filename'])) {
                                $message->addAttachment($datas['filename'], $datas['name']);
                            }
                        } catch (\Exception $ex) {
                            // @todo
                        }
                    }
                    if ($oneEmail->getEmailEdi2Id()) {
                        try {
                            $editionService = \FreeFW\DI\DI::get('FreeFW::Service::Edition');
                            $datas = $editionService->printEdition(
                                $oneEmail->getEmailEdi2Id(),
                                $p_lang_id,
                                $p_model
                            );
                            if (isset($datas['filename']) && is_file($datas['filename'])) {
                                $message
                                    ->setMsgPj2($datas['filename'])
                                    ->setMsgPj2Name($datas['name'])
                                ;
                            }
                        } catch (\Exception $ex) {
                            // @todo
                        }
                    }
                }
                $message
                    ->setMsgObjectName($p_model->getApiType())
                    ->setMsgObjectId($p_model->getApiId())
                    ->setMsgSubject($subject)
                    ->setMsgBody($body)
                    ->setMsgStatus(\FreeFW\Model\Message::STATUS_WAITING)
                    ->setMsgType(\FreeFW\Model\Message::TYPE_EMAIL)
                    ->setLangId($p_lang_id)
                    ->setReplyTo($oneEmail->getEmailReplyTo())
                    ->setFrom($oneEmail->getEmailFrom(), $oneEmail->getEmailFromName())
                ;
            }
        }
        return $message;
    }

    /**
     * Find email by code and lang
     *
     * @param string $p_code
     * @param number $p_lang_id
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function getEmail($p_code, $p_lang_id = null)
    {
        /**
         * @var \FreeFW\Model\Email $email
         */
        $email = \FreeFW\Model\Email::findFirst(
            [
                'email_code' => $p_code,
                'lang_id'    => $p_lang_id,
            ]
        );
        /**
         * @var \FreeFW\Model\EmailLang $emailLang
         */
        $emailLang = null;
        if ($email) {
            $emailLang = \FreeFW\Model\EmailLang::findFirst(
                [
                    'email_id' => $email->getEmailId(),
                    'lang_id'  => $p_lang_id
                ]
            );
            if (!$emailLang) {
                $emailLang = \FreeFW\Model\EmailLang::findFirst(
                    [
                        'email_id' => $email->getEmailId(),
                        'lang_id'  => $email->getLangId()
                    ]
                );
            }
        }
        $result = null;
        if ($emailLang) {
            $result = new \FreeFW\Mail\Email();
            $result
                ->setSubject($emailLang->getEmaillSubject())
                ->setHtmlBody($emailLang->getEmaillBody())
                ->setFrom($email->getEmailFrom(), $email->getEmailFromName())
                ->setReplyTo($email->getEmailReplyTo())
            ;
        }
        // @todo : en by default...
        return $result;
    }
}
