<?php
namespace FreeFW\Tools\Vad;

/**
 *
 * @author jeromeklam
 *
 */
interface VadInterface
{

    /**
     * Constants
     * @var string
     */
    const CST_PARAM_EMAIL  = 'EMAIL';
    const CST_PARAM_CMD    = 'CMD';
    const CST_PARAM_AMOUNT = 'AMOUNT';
    const CST_PARAM_MODE   = 'MODE';
    const CST_PARAM_DAYS   = 'DAYS';
    const CST_PARAM_REFDET = 'REFDET';
    const CST_PARAM_EXER   = 'EXER';
    const CST_CUST_ID      = 'CUSID';

    /**
     * Set request parameters
     *
     * @param array $p_params
     *
     * @return static
     */
    public function setParameters(array $p_params = array());

    /**
     * Return form
     *
     * @param string $p_html_model
     *
     * @return string
     */
    public function getForm($p_html_model);
}
