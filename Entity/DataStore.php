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

namespace Equinoxe\DataStoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * 
 * @orm:Entity
 * @orm:InheritanceType("JOINED")
 * @orm:DiscriminatorColumn(name="discr", type="string")
 * @orm:DiscriminatorMap({
 *      "orm" = "Equinoxe\DataStoreBundle\Entity\Orm\OrmDataStore"
 * })
 */
class DataStore
{
    /**
     * Unique Id of the data store.
     * 
     * @var integer
     * @orm:Id
     * @orm:Column(type="integer")
     * @orm:GeneratedValue(strategy="IDENTITY")
     */
    protected $uid;


    /**
     * Name of the data store. Unique within a workflow.
     *
     * @var string
     * @orm:Column(type="string")
     */
    protected $name;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getUid() {
        return $this->uid;
    }

    public function setUid($uid) {
        if(!is_numeric($uid)) {
            throw new \InvalidArgumentException('Only integer is allowed here.');
        }
        $this->uid = $uid;
    }

    /**
     * Returns the name of the Datastore
     *
     * @return string the Name of the Datastore
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the Name for a datastore.
     *
     * @param string $name The new name of the Datastore.
     */   
    public function setName($name) {
        $this->name = $name;
    }

}
