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

use Equinoxe\DataStoreBundle\Entity\DataStore;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class provides an object relational mapper data store for Doctrine entities.
 *
 * There are several Doctrine entities shipped with DataStoreBundle to use normal data
 * types like string with this data store. @See StringRecord
 * 
 * @orm:Entity
 */
class OrmDataStore extends DataStore
{
    /**
     * Collection of the records assigned to this data store.
     *
     * @var ArrayCollection
     * @orm:ManyToMany(targetEntity="Equinoxe\DataStoreBundle\Entity\Orm\DataStoreRecord",cascade={"All"})
     * @orm:JoinTable(name="ormdatastore_record",
     *      joinColumns={@orm:JoinColumn(name="datastore_id", referencedColumnName="uid")},
     *      inverseJoinColumns={@orm:JoinColumn(name="record_id", referencedColumnName="uid", unique="true")}
     *      )
     */
    protected $records;

    /**
     * Maps the records to an associative array.
     *
     * @var array
     */
    private $recordsTransformed = null;

    public function __construct($container)
    {
        $this->records = new ArrayCollection();
        parent::__construct($container);
    }

    /**
     * Getter for $recordsTransformed.
     *
     * @return array
     */
    public function getRecords() {
        if ($this->recordsTransformed == null) {
            $this->transform();
        }
        return $this->recordsTransformed;
    }

    public function getRecordsRaw()
    {
        return $this->records;
    }

    /**
     * Builds an associative array from $records and assigns it to $recordsTransformed.
     */
    private function transform()
    {
        $this->recordsTransformed = array();
        foreach($this->records as $key => $record) {
            $this->recordsTransformed[$record->getKey()] = $record;
        }
    }

    /**
     * Setter for $records.
     *
     * @param ArrayCollection $records The new record.
     */
    public function setRecords($records) {
        $this->records = $records;
    }

    /**
     * Adds a new record to this store.
     *
     * The new record must have a key.
     *
     * @param DataStoreRecord $record The new record.
     */
    public function add(DataStoreRecord $record)
    {
        $this->records->set($record->getKey(), $record);
        if ($this->recordsTransformed == null) {
            $this->recordsTransformed = array();
        }
        $this->recordsTransformed[$record->getKey()] = $record;
    }

    /**
     * Returns the record with the specified key.
     *
     * @param string $key The key of the record.
     *
     * @return DataStoreRecord The requested record.
     */
    public function get($key)
    {
        if ($this->recordsTransformed == null) {
            $this->transform();
        }
        return $this->recordsTransformed[$key];
    }

    /**
     * Sets the record to the specified key.
     *
     * @param string $key The index of the record.
     *
     * @param DataStoreRecord $dataStoreRecord The record.
     * 
     */
    public function set($key,$dataStoreRecord)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        //TODO: this may suck in performanceon stores with a lot of keys..
        $records=$this->getRecordsRaw();
        foreach ( $this->getRecordsRaw() as $rkey => $record ) {
           if ( $record->getKey() == $key ) {
               $this->records->removeElement($record);
               $em->flush();
               $em->remove($record);
               $em->flush();
           }
        }
        $dataStoreRecord->setKey($key);
        $this->add($dataStoreRecord);
    }

     /**
     * Returns the serialized content the Datastore.
     *
     * @return string the serialized content of the Datastore.
     */
    public function saveSnapShot(){
        return serialize($this->records);
    }

    /**
     * Returns the serialized content the Datastore.
     *
     * @param string $snapshot The snashot of an Datastore to revert to.
     */
    public function restoreSnapShot($snapshot){
       $em = $this->container->get('doctrine.orm.entity_manager');
       $records = unserialize($snapshot);
       foreach ( $this->records as $oldrecord ){
           $em->remove($oldrecord);
           $this->records->removeElement($oldrecord);
       }
       foreach ($records as $newrecord) {
           $em->persist($newrecord);
           $this->add($newrecord);
       }
       $em->flush();
    }

    /**
     * Sets the container for the datastore.
     *
     * @param string $container the container.
     *
     *
     */
    public function setContainer($container){
        $this->container=$container;
    }
}
