<?php
namespace FreeFW\Service;

/**
 *
 * @author jeromeklam
 *
 */
class Translation extends \FreeFW\Core\Service
{
    /**
     * Clean / check notifications
     *
     * @return void
     */
    public function import($p_params)
    {
        $lang = 'fr';
        if (isset($p_params['lang'])) {
            $lang = $p_params['lang'];
        }
        $filename = APP_ROOT . 'data/' . $lang . '.json';
        if (isset($p_params['filename'])) {
            $filename = $p_params['filename'];
        }
        if (is_file($filename)) {
            $content = json_decode(file_get_contents($filename), true);
            if ($content) {
                foreach($content as $key => $value) {
                    /**
                     * @var \FreeFW\Model\Translation $ontTr
                     */
                    $oneTr = \FreeFW\Model\Translation::findFirst(['tr_key' => $key]);
                    if (!$oneTr) {
                        $parts = explode('.', $key);
                        array_pop($parts);
                        $start = '';
                        foreach ($parts as $onePart) {
                            if ($start !== '') {
                                $start .= '.';
                            }
                            $start .= $onePart;
                            $secTr = \FreeFW\Model\Translation::findFirst(['tr_key' => $start]);
                            if (!$secTr) {
                                $secTr = new \FreeFW\Model\Translation();
                                $secTr
                                    ->setTrKey($start)
                                    ->setTrHtml(false)
                                    ->setTrType(\FreeFW\Model\Translation::TYPE_NODE)
                                ;
                                $secTr->create();
                            }
                        }
                        $oneTr = new \FreeFW\Model\Translation();
                        $oneTr
                            ->setTrKey($key)
                            ->setTrHtml(false)
                            ->setTrType(\FreeFW\Model\Translation::TYPE_SHEET)
                        ;
                    }
                    $setter = 'setTrLang' . \FreeFW\Tools\PBXString::toCamelCase($lang, true);
                    $oneTr->$setter($value);
                    if ($oneTr->getTrId() > 0) {
                        $oneTr->save();
                    } else {
                        $oneTr->create();
                    }
                }
            }
        }
    }   
}
