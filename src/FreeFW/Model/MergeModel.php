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
     * Arrays
     * @var array
     */
    protected $arrays = [];

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
     * Types
     * @var array
     */
    protected $types = [];

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
     * Generic datas
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
     * @param mixed   $p_block
     * @param boolean $p_array
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addBlock($p_block, $p_array = false)
    {
        $blocks = $p_block;
        if (!is_array($blocks)) {
            $blocks = explode(',', $p_block);
        }
        foreach ($blocks as $oneBlock) {
            if (!in_array(trim($oneBlock), $this->blocks)) {
                $this->blocks[] = trim($oneBlock);
                if ($p_array) {
                    $this->arrays[] = $oneBlock;
                }
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
     * @param string $p_type
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addField($p_name, $p_title = '', $p_type = null, $p_block = '')
    {
        $name = $p_name;
        if ($p_block != '') {
            $name = $p_block . '_' . $name;
        }
        $this->fields[] = $name;
        if ($p_title !== '') {
            $this->titles[$name] = $p_title;
        } else {
            $this->titles[$name] = $name;
        }
        $this->types[$name] = $p_type;
        return $this;
    }

    /**
     * Aff fields, ...
     */
    public function addFields($p_fields, $p_titles, $p_types, $p_block)
    {
        foreach ($p_fields as $oneField) {
            $this->addField($oneField, $p_titles[$oneField], $p_types[$oneField]);
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
     * Get title
     *
     * @param string $p_name
     * @param string $oneBlock
     *
     * @return boolean
     */
    public function getTitle($p_name, $p_block = '')
    {
        $name = $p_name;
        if ($p_block != '')  {
            $name = $p_block . '_' . $name;
        }
        if (isset($this->titles[$p_name])) {
            return $this->titles[$p_name];
        }
        if (isset($this->titles[$name])) {
            return $this->titles[$name];
        }
        return false;
    }

    /**
     * Get types
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Get field type
     *
     * @param string $p_name
     * @param string $p_block
     *
     * @return boolean
     */
    public function getType($p_name, $p_block = '')
    {
        $name = $p_name;
        if ($p_block != '')  {
            $name = $p_block . '_' . $name;
        }
        if (isset($this->types[$p_name])) {
            return $this->types[$p_name];
        }
        if (isset($this->types[$name])) {
            return $this->types[$name];
        }
        return false;
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
     * @param array   $p_data
     * @param string  $p_block
     * @param boolean $p_replace
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addData(array $p_data, $p_block = 'default', $p_replace = true)
    {
        if (!array_key_exists($p_block, $this->datas)) {
            $this->datas[$p_block] = [];
        }
        if (in_array($p_block, $this->arrays)) {
            if ($p_replace) {
                $this->datas[$p_block] = $p_data;
            } else {
                array_push($this->datas[$p_block], $p_data);
            }
        } else {
            if ($p_replace) {
                $this->datas[$p_block] = $p_data;
            } else {
                $this->datas[$p_block] = array_merge($this->datas[$p_block], $p_data);
            }
        }
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
     * @param array $p_datas
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function addGenericData(array $p_datas, $p_block = 'generic')
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

    /**
     * Block is an array ?
     *
     * @param string $p_block
     *
     * @return boolean
     */
    public function isBlockAnArray($p_block)
    {
        return in_array($p_block, $this->arrays);
    }

    /**
     * Convert to array
     *
     * @return array[]
     */
    public function __toArray()
    {
        $datas = [];
        foreach ($this->blocks as $oneBlock) {
            if (array_key_exists($oneBlock, $this->datas)) {
                foreach ($this->datas[$oneBlock] as $key => $value) {
                    $datas[$oneBlock . '.' . $key] =  $value;
                }
            }
        }
        foreach($this->getGenericBlocks() as $blockName) {
            $gDatas = $this->getGenericDatas($blockName);
            foreach ($gDatas as $key => $val) {
                $datas[$blockName . '.' . $key] = $val;
            }
        }
        return $datas;
    }
}
