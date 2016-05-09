<?php
/**
 * Created by PhpStorm.
 * User: milos
 * Date: 9.5.16.
 * Time: 11.52
 */
namespace Freshdesk\Model;

class Product extends Base
{
    const RESPONSE_KEY = 'product';

    protected $id = null;
    protected $name = null;
    protected $description = null;
    protected $created_at = null;
    protected $update_at = null;

    public function toJsonData()
    {
        return json_encode(
        );
    }

    public function getId(){
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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