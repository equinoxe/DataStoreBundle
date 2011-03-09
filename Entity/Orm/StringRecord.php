<?php
/**
 * This file is part of the DataStoreBundle package.
 *
 * @package    Core
 * @subpackage Main
 * @author     Timon Rapp <rapp@equinoxe.info>
 * @copyright  Equinoxe GmbH <info@equinoxe.de>
 * @license    GPL V3
 */

namespace Equinoxe\DataStoreBundle\Entity\Orm;

use Equinoxe\DataStoreBundle\Entity\Orm\DataStoreRecord;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @orm:Entity
 */
class StringRecord extends DataStoreRecord
{
    function __construct($value = '') {
        $this->value = $value;
    }

    /**
     *
     * @var string
     * @orm:Column(type="string")
     */
    protected $value;

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }


}