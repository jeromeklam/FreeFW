<?php
namespace FreeFW\Behaviour;

use Psr\Http\Message\ResponseInterface;
use \FreeFW\Constants as FFCST;
use FreeFW\Constants;

/**
 *
 * @author jeromeklam
 *
 */
trait HttpFactoryTrait
{

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new \GuzzleHttp\Psr7\Response($code, [], $reasonPhrase);
    }

    /**
     * Return a response of type error
     *
     * @desc permet de retourner une réponse de type error. Ici le code http retourné dépend de la valeur de $p_codeHttp.
     *
     * @param string|object $p_reasonPhrase les circonstances de l'erreur
     * @param int $p_code code interne au FW (cf Constants : 6660001,...)
     * @param int $p_codeHttp code Http à renvoyer (404,409,412,...)
     *
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     * @return \Psr\Http\Message\Response
     */
    protected function _createErrorResponse($p_reasonPhrase, int $p_code, int $p_codeHttp): ResponseInterface
    {
        if (is_string($p_reasonPhrase)) {
            $response = new \FreeFW\Model\Error();
            $response = $response->addError($p_code, $p_reasonPhrase, $p_codeHttp);

            return $this->createResponse($response->getErrors()[0]->getType(), $response);
        }

        return $this->createResponse($p_codeHttp, $p_reasonPhrase);
    }

    /**
     *
     * Return a response of type error
     *
     * @desc permet de retourner une réponse de type error. Ici le code http retourné dépend de la valeur de $p_code.
     *
     * @param int $p_code code interne au FW (cf Constants : 6660001,...)
     * @param string|object $p_reasonPhrase les circonstances de l'erreur
     *
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     * @return \Psr\Http\Message\Response
     */
    public function createErrorResponse(int $p_code, $p_reasonPhrase = null): ResponseInterface
    {
        $p_code_mess = [
            FFCST::ERROR_NOT_INSERT         => 'not insert',            // 412
            FFCST::ERROR_NOT_DELETE         => 'not delete',
            FFCST::ERROR_NOT_UPDATE         => 'not update',

            FFCST::ERROR_NOT_FOUND          => 'not found',             // 404
            FFCST::ERROR_ID_IS_UNAVALAIBLE  => 'id is unavailable',

            FFCST::ERROR_ID_IS_MANDATORY    => 'id is mandatory',       // 409
            FFCST::ERROR_NO_DATA            => 'no data'
        ];

        // opérateur Null coalescent
        //
        // Il retourne le 1er opérande s'il existe et n'a pas une valeur NULL, et retourne le second opérande sinon.
        // L'opérateur permet de faire du chaînage : Ceci va retourner la première valeur définie
        // respectivement dans $p_reasonPhrase, $p_code_mess[$p_code] et ''.

        $reasonPhrase = $p_reasonPhrase ?? $p_code_mess[$p_code] ?? '';

        switch ($p_code) {
            case FFCST::ERROR_NOT_INSERT :
            case FFCST::ERROR_NOT_DELETE :
            case FFCST::ERROR_NOT_UPDATE :
                $codeHttp = 412;
                break;

            case FFCST::ERROR_NOT_FOUND :
            case FFCST::ERROR_ID_IS_UNAVALAIBLE :
                $codeHttp = 404;
                break;

//          case FFCST::ERROR_ID_IS_MANDATORY :
//          case FFCST::ERROR_NO_DATA :

            default :
                $codeHttp = 409;
                break;
        }

        return $this->_createErrorResponse($reasonPhrase, $p_code, $codeHttp);
    }

    /**
     *
     * Return a response of type success
     *
     * @desc permet de retourner une réponse de type error. Ici le code http retourné dépend de la valeur de $p_code.
     *
     * @param int $p_code code interne au FW (cf Constants : SUCCESS_???)
     * @param string|object $p_reasonPhrase les données en retour
     *
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     * @return \Psr\Http\Message\Response
     */
    public function createSuccessResponse(int $p_code, $p_reasonPhrase = null): ResponseInterface
    {
        switch ($p_code) {
            case FFCST::SUCCESS_RESPONSE_EMPTY :
                $codeHttp = 204;
                break;

            case FFCST::SUCCESS_RESPONSE_ADD :
                $codeHttp = 201;
                break;

            case FFCST::SUCCESS_RESPONSE_OK :
            default :
                $codeHttp = 200;
                break;
        }

        return $this->createResponse($codeHttp, $p_reasonPhrase);
    }

    /**
     *
     * Return a response positive avec info
     *
     * @param string|object $p_reasonPhrase les données en retour
     *
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     * @return \Psr\Http\Message\Response
     */
    public function createSuccessOkResponse($p_reasonPhrase): ResponseInterface
    {
        return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_OK,$p_reasonPhrase);
    }

    /**
     *
     * Return a response positive sans info
     *
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     * @return \Psr\Http\Message\Response
     */
    public function createSuccessEmptyResponse(): ResponseInterface
    {
        return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_EMPTY);
    }

    /**
     *
     * Return a response positive avec info de type ADD
     *
     * @param string|object $p_reasonPhrase les données en retour
     *
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     * @return \Psr\Http\Message\Response
     */
    public function createSuccessAddResponse($p_reasonPhrase): ResponseInterface
    {
        return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_ADD,$p_reasonPhrase);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     */
    public function createMimeTypeResponse($p_filename,$p_content = null): ResponseInterface
    {
        $type    = \GuzzleHttp\Psr7\mimetype_from_filename($p_filename);
        if (!$type) {
            $type = 'application/octet-stream';
        }
        $content = $p_content;
        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => $type,
            'Content-Disposition' => 'attachment; filename="' . $p_filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ];
        return new \FreeFW\Psr7\Response(200, $headers, $content);
    }
}
