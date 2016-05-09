<?php
/**
 * Created by PhpStorm.
 * User: milos
 * Date: 9.5.16.
 * Time: 13.31
 */

namespace Freshdesk\Model;

use \InvalidArgumentException;

class Company extends Base
{
    const RESPONSE_KEY = 'company';

    protected $id = null;
    protected $name = '';
    protected $note = '';
    protected $description = '';
    protected $domains = [];
    protected $created_at = null;
    protected $update_at = null;

    public function toJsonData()
    {
        return json_encode(
            [
                'name'=>$this->name
            ]
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public  function getDomains()
    {
        return $this->domains;
    }

    public function setDomains($domains){
        if(is_array($domains)){
            $this->domains = $domains;
        }else{
            throw new InvalidArgumentException('This params should be an array');
        }
    }

    public function getCreateAt()
    {
        return $this->created_at;
    }

    public function setCreateAt($createAt)
    {
        $this->created_at = $createAt;
    }

    public function getUpdateAt()
    {
        return $this->update_at;
    }

    public function setUpdateAt($updateAt)
    {
        $this->update_at = $updateAt;
    }

}