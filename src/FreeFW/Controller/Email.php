<?php
namespace FreeFW\Controller;

/**
 * Model controller
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Core\ApiController
{

    public function sendOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id = null, $p_lang = null)
    {
        $this->logger->debug('FreeFW.EmailController.sendOne.start');
        $code = \FreeFW\Constants::ERROR_NOT_FOUND; // 404
        $data = null;
        $sso = \FreeFW\DI\DI::getShared('sso');
        $user = $sso->getUser();
        $group = $sso->getUserGroup();
        $email = \FreeFW\Model\Email::findFirst(
            [ 'email_id' => $p_id ]
        );
        $emailVersion = null;
        if ($email) {
            $object = $email->getEmailObjectName();
            $class  = '\\' . str_replace('_', '\\Model\\', $object);
            if (class_exists($class)) {
                $instance = $class::findFirst();
                if ($instance) {
                    $filters = [
                        'email_id' => $email->getEmailId()
                    ];
                    $emailService = \FreeFW\DI\DI::get("FreeFW::Service::Email");
                    $lang         = \FreeFW\Model\Lang::findFirst(['lang_code' => $p_lang]);
                    $lCode        = 368;
                    if ($lang) {
                        $lCode = $lang->getLangId();
                    }
                    $message = $emailService->getEmailAsMessage($filters, $lCode, $instance, true, $group->getGrpId());
                    if ($message) {
                        $message
                            ->addDest($user->getUserLogin())
                            ->setDestId($user->getUserId())
                        ;
                        if ($message->create()) {
                            $this->logger->debug('FreeFW.EmailController.sendOne.end');
                            return $this->createSuccessOkResponse($message); // 200
                        }
                    }
                }
            }
        }
        $this->logger->debug('FreeFW.EmailController.sendOne.error');
        return $this->createErrorResponse($code, $data);
    }
}
