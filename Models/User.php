<?php


class User
{
    protected
        $_id,
        $_username,
        $_password,
        $_fullname,
        $_datecreated,
        $_avatar,
        $_postCount,
        $_replyCount;

    public function __construct($dbRow)
    {
        $this->_id = $dbRow['u_id'];
        $this->_username = $dbRow['u_username'];
        $this->_password = $dbRow['u_password'];
        $this->_fullname = $dbRow['u_fullname'];
        $this->_datecreated = $dbRow['u_datecreated'];
        $this->_avatar = $dbRow['u_avatar'];
        $this->_postCount = $dbRow['u_postcount'];
        $this->_replyCount = $dbRow['u_replycount'];
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
    public function getFullname()
    {
        return $this->_fullname;
    }

    /**
     * @return mixed
     */
    public function getDatecreated()
    {
        return $this->_datecreated;
    }

    public function getAvatar()
    {
        return $this->_avatar;
    }

    public function getPostCount()
    {
        return $this->_postCount;
    }

    public function getReplyCount()
    {
        return $this->_replyCount;
    }
}