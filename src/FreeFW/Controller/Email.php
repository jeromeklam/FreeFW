<?php
namespace FreeFW\Controller;

/**
 * Model controller
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Core\ApiController
{

    /**
     * Comportement
     */
    use \FreeAsso\Controller\Behaviour\Group;

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
            $object = $email->getEmailObjectName();
            $class  = '\\' . str_replace('_', '\\Model\\', $object);
            if (class_exists($class)) {
                $instance = $class::findFirst();
                if ($instance) {
                    $filters = [
                        'email_id' => $email->getEmailId()
                    ];
                    $emailService = \FreeFW\DI\DI::get("FreeFW::Service::Email");
                    $message = $emailService->getEmailAsMessage($filters, 368, $instance);
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
