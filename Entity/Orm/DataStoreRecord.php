<?php
/**
 * This file is part of the Flexiflow package.
 *
 * @package    Core
 * @subpackage Main
 * @author     Timon Rapp <rapp@equinoxe.info>
 * @copyright  Equinoxe GmbH <info@equinoxe.de>
 * @license    GPL V3
 */

namespace Equinoxe\DataStoreBundle\Entity\Orm;

use Equinoxe\DataStoreBundle\Entity\DataStore;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Base class for Records.
 *
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "string" = "Equinoxe\DataStoreBundle\Entity\Orm\StringRecord"
 * })
 */
abstract class DataStoreRecord
{
    /**
     * Unique Id of the data store.
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $uid;

    /**
     *
     * @var string
     * @ORM\Column(type="string",name="record_key")
     */
    protected $key;


    protected $value;


    public function getUid() {
        return $this->uid;
    }

    public function setUid($uid) {
        $this->uid = $uid;
    }

    public function getKey() {
        return $this->key;
    }

    public function setKey($key) {
        if (ctype_digit($key)) {
            throw new Exception('Numbers are not permitted as index here.');
        }
        $this->key = $key;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }
}