<?php
/**
 * Utilitaires Dates
 *
 * @author jeromeklam
 * @package Date
 * @category Tools
 */
namespace FreeFW\Tools;

/**
 * Classe de gestion des dates
 * @author jeromeklam
 */
class Address
{

    /**
     * Firstname
     * @var string
     */
    protected $firstname = null;

    /**
     * Lastname
     * @var string
     */
    protected $lastname = null;

    /**
     * Fullname
     * @var string
     */
    protected $fullname = null;

    /**
     * Address1
     * @var string
     */
    protected $address1 = null;

    /**
     * Address2
     * @var string
     */
    protected $address2 = null;

    /**
     * Address3
     * @var string
     */
    protected $address3 = null;

    /**
     * postcode
     * @var string
     */
    protected $postcode = null;

    /**
     * Town
     * @var string
     */
    protected $town = null;

    /**
     * Country
     * @var string
     */
    protected $country = null;

    /**
     * Lines
     * @var array
     */
    protected $lines = [];

    /**
     * Get firstname
     *
     * @return  string
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set firstname
     *
     * @param  string  $firstname  Firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return  string
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set lastname
     *
     * @param  string  $lastname  Lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get fullname
     *
     * @return  string
     */ 
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set fullname
     *
     * @param  string  $fullname  Fullname
     *
     * @return  self
     */ 
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * Get address1
     *
     * @return  string
     */ 
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address1
     *
     * @param  string  $address1  Address1
     *
     * @return  self
     */ 
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * Get address2
     *
     * @return  string
     */ 
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set address2
     *
     * @param  string  $address2  Address2
     *
     * @return  self
     */ 
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * Get address3
     *
     * @return  string
     */ 
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * Set address3
     *
     * @param  string  $address3  Address3
     *
     * @return  self
     */ 
    public function setAddress3($address3)
    {
        $this->address3 = $address3;
        return $this;
    }

    /**
     * Get postcode
     *
     * @return  string
     */ 
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set postcode
     *
     * @param  string  $postcode  postcode
     *
     * @return  self
     */ 
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * Get town
     *
     * @return  string
     */ 
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set town
     *
     * @param  string  $town  Town
     *
     * @return  self
     */ 
    public function setTown($town)
    {
        $this->town = $town;
        return $this;
    }

    /**
     * Get country
     *
     * @return  string
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param  string  $country  Country
     *
     * @return  self
     */ 
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get lines
     *
     * @return  array
     */ 
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Get line
     *
     * @param integer $p_idx
     * 
     * @return string
     */
    public function getLine($p_idx)
    {
        if (isset($this->lines[$p_idx])) {
            return $this->lines[$p_idx];
        }
        return '';
    }

    /**
     * Flush
     *
     * @return  self
     */
    public function flush()
    {
        $this->lines = [];
        return $this;
    }

    /**
     * Add new line
     *
     * @param string  $p_line
     * @param boolean $p_more
     * @param integer $p_max_lines
     * 
     * @return  self
     */
    public function addLine($p_line, $p_more = true, $p_max_lines = 99)
    {
        if (count($this->lines) < $p_max_lines) {
            $newLine = strtoupper(trim(\FreeFW\Tools\PBXString::withoutAccent($p_line)));
            if ($newLine != '') {
                $first   = \FreeFW\Tools\PBXString::truncString($newLine, 38, '', true);
                $this->lines[] = $first;
                if ($p_more && count($this->lines) < $p_max_lines) {
                    $newLine = trim(str_replace($first, '', $newLine));
                    if ($newLine != '') {
                        $second  =  \FreeFW\Tools\PBXString::truncString($newLine, 38, '', true);
                        $this->lines[] = $second;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Compute lines
     *
     * @return  boolean
     */
    public function compute()
    {
        $this->lines = [];
        if ($this->fullname != '') {
            $first = trim($this->fullname);
        } else {
            $first = trim($this->firstname) . ' ' . trim($this->lastname);
        }
        $this->addLine($first, true, 4);
        $this->addLine($this->address1, true, 4);
        $this->addLine($this->address2, true, 4);
        $this->addLine($this->address3, true, 4);
        $this->addLine($this->postcode . ' ' . $this->town, true, 5);
        $this->addLine($this->country, true, 6);
        return true;
    }
}
