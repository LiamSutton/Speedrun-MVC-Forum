<?php


class User
{
    protected $_id, $_username, $_password, $_firstname, $_lastname, $_datecreated;

    public function __construct($id, $username, $password, $firstname, $lastname, $datecreated)
    {
        $this->_id = $id;
        $this->_username = $username;
        $this->_password = $password;
        $this->_firstname = $firstname;
        $this->_lastname = $lastname;
        $this->_datecreated = $datecreated;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->_firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     * @return mixed
     */
    public function getDatecreated()
    {
        return $this->_datecreated;
    }

}