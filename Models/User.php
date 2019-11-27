<?php


class User
{
    protected $_id, $_username, $_password, $_firstname, $_lastname, $_datecreated;

    public function __construct($dbRow)
    {
        $this->_id = $dbRow['u_id'];
        $this->_username = $dbRow['u_username'];
        $this->_password = $dbRow['u_password'];
        $this->_firstname = $dbRow['u_firstname'];
        $this->_lastname = $dbRow['u_lastname'];
        $this->_datecreated = $dbRow['u_datecreated'];
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