<?php
namespace FreeFW\Model;

/**
 *
 * @author jerome.klam
 *
 */
class MergeModel {

    /**
     * Blocks
     * @var array
     */
    protected $blocks = [];

    /**
     * Titles
     * @var array
     */
    protected $titles = [];

    /**
     * Fields
     * @var array
     */
    protected $fields = [];

    /**
     * Datas
     * @var array
     */
    protected $datas = [];

    /**
     * Flush blocks
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function flushBlocks()
    {
        $this->blocks = [];
        return $this;
    }

    /**
     * Add new block
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addBlock($p_block)
    {
        $this->blocks[] = $p_block;
        return $this;
    }

    /**
     * Get blocks
     *
     * @return array
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * Flush fields and titles
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function flushFields()
    {
        $this->fields = [];
        $this->titles = [];
        return $this;
    }

    /**
     * Add field
     *
     * @param string $p_name
     * @param string $p_title
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addField($p_name, $p_title = '')
    {
        $this->fields[] = $p_name;
        if ($p_title !== '') {
            $this->titles[] = $p_title;
        } else {
            $this->titles[] = $p_name;
        }
        return $this;
    }

    /**
     * Get fields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get titles
     *
     * @return array
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * Flush datas
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function flushDatas()
    {
        $this->datas = [];
        return $this;
    }

    /**
     * Add data
     *
     * @param object $p_data
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addData($p_data)
    {
        $this->datas[] = $p_data;
        return $this;
    }

    /**
     * Get datas
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->datas;
    }

    /**
     * Get datas as simple array of array
     *
     * @return array
     */
    public function getDatasAsArray()
    {
        return json_decode(json_encode($this->datas), true);
    }

    /**
     * Get blocks as string, separeted by ,
     *
     * @return string
     */
    public function getBlocksAsString()
    {
        return implode(',', $this->blocks);
    }
}
