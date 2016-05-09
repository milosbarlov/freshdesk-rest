<?php
/**
 * Created by PhpStorm.
 * User: milos
 * Date: 9.5.16.
 * Time: 14.57
 */

namespace Freshdesk\Model;

use \InvalidArgumentException;

class Agent extends Base
{
    const RESPONSE_KEY = 'agent';

    protected $available = true;
    protected $id = null;
    protected $contact = [];

    public function toJsonData()
    {

    }

    public function getAvailable()
    {
        return $this->available;
    }

    public function setAvailable($available)
    {
        $this->available = $available;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
       $this->contact = $contact;
    }

    public function getEmail()
    {
        return $this->contact->email;
    }

    public function getName()
    {
        return $this->contact->name;
    }

}