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
    protected $datas = ['default' => []];

    /**
     * Generic blocks
     * @var array
     */
    protected $generic_blocks = [];

    /**
     *
     * @var array
     */
    protected $generic_datas = [];

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
        $blocks = $p_block;
        if (!is_array($blocks)) {
            $blocks = explode(',', $p_block);
        }
        foreach ($blocks as $oneBlock) {
            if (!in_array(trim($oneBlock), $this->blocks)) {
                $this->blocks[] = trim($oneBlock);
            }
        }
        return $this;
    }

    /**
     * Get blocks
     *
     * @return array
     */
    public function getBlocks($p_add_generic = false)
    {
        if (!$p_add_generic) {
            return $this->blocks;
        }
        return array_merge($this->blocks, $this->generic_blocks);
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
        $this->datas = ['default' => []];
        return $this;
    }

    /**
     * Add data
     *
     * @param object $p_data
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addData($p_data, $p_block = 'default')
    {
        if (!array_key_exists($p_block, $this->datas)) {
            $this->datas[$p_block] = [];
        }
        $this->datas[$p_block] = array_merge($this->datas[$p_block], $p_data);
        return $this;
    }

    /**
     * Get datas
     *
     * @return array
     */
    public function getDatas($p_block = 'default')
    {
        $datas = [];
        if (array_key_exists($p_block, $this->datas)) {
            $datas = $this->datas[$p_block];
        }
        return $datas;
    }

    /**
     * Get blocks as string, separeted by ,
     *
     * @return string
     */
    public function getBlocksAsString($p_add_generic = false)
    {
        $blocks = $this->blocks;
        if ($p_add_generic) {
            $blocks = array_merge($blocks, $this->generic_blocks);
        }
        return implode(',', $blocks);
    }

    /**
     * Add generic block
     *
     * @param string $p_block
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addGenericBlock($p_block)
    {
        $blocks = $p_block;
        if (!is_array($blocks)) {
            $blocks = explode(',', $p_block);
        }
        foreach ($blocks as $oneBlock) {
            if (!in_array(trim($oneBlock), $this->blocks)) {
                $this->generic_blocks[] = trim($oneBlock);
            }
        }
        return $this;
    }

    /**
     * Get generic blocks
     *
     * @return array
     */
    public function getGenericBlocks()
    {
        return $this->generic_blocks;
    }

    /**
     * Add generic datas
     *
     * @param \StdClass $p_datas
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addGenericData($p_datas, $p_block = 'generic')
    {
        if (!array_key_exists($p_block, $this->generic_datas)) {
            $this->generic_datas[$p_block] = [];
        }
        $this->generic_datas[$p_block] = array_merge($this->generic_datas[$p_block], $p_datas);
        return $this;
    }

    /**
     * Get datas from generic block
     *
     * @param string $p_block
     *
     * @return array
     */
    public function getGenericDatas($p_block)
    {
        $datas = [];
        if (array_key_exists($p_block, $this->generic_datas)) {
            $datas = $this->generic_datas[$p_block];
        }
        return $datas;
    }
}
