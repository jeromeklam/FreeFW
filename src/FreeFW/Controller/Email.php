<?php
namespace FreeFW\Controller;

/**
 * Model controller
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Core\ApiController
{

    public function sendOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id = null)
    {
        $this->logger->debug('FreeFW.EmailController.sendOne.start');
        $code = \FreeFW\Constants::ERROR_NOT_FOUND; // 404
        $data = null;
        $sso = \FreeFW\DI\DI::getShared('sso');
        $user = $sso->getUser();
        $email = \FreeFW\Model\Email::findFirst(
            [ 'email_id' => $p_id ]
        );
        $emailVersion = null;
        if ($email) {
            foreach ($email->getVersions() as $oneVersion) {
                if ($oneVersion->getLangId() == $user->getLangId()) {
                    $emailVersion = $oneVersion;
                    break;
                }
            }
            if ($emailVersion === null) {
                foreach ($email->getVersions() as $oneVersion) {
                    $emailVersion = $oneVersion;
                    if ($oneVersion->getLangId() == $email->getLangId()) {
                        break;
                    }
                }
            }
        }
        if ($emailVersion) {
            $group = $sso->getUserGroup();
            $grpId = 4;
            if ($group) {
                $grpId = $group->getGrpId();
            }
            $message = new \FreeFW\Model\Message();
            $subject = $oneVersion->getEmaillSubject();
            $body    = $oneVersion->getEmaillBody();
            /**
             * @var \FreeFW\Model\Message $message
             */
            $message
                ->setMsgObjectName($email->getEmailObjectName())
                ->setMsgObjectId(1)
                ->setMsgSubject($subject)
                ->setMsgBody($body)
                ->setMsgStatus(\FreeFW\Model\Message::STATUS_WAITING)
                ->setMsgType(\FreeFW\Model\Message::TYPE_EMAIL)
                ->setLangId($oneVersion->getLangId())
                ->setReplyTo($email->getEmailReplyTo())
                ->setFrom($email->getEmailFrom(), $email->getEmailFromName())
                ->addDest($user->getUserLogin())
            ;
            $message->create();
            return $this->createSuccessOkResponse($message); // 200
        }
        $this->logger->debug('FreeFW.EmailController.sendOne.end');
        return $this->createErrorResponse($code, $data);
    }
}
