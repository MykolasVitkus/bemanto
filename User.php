<?php

class User
{
    protected $userEmail;
    protected $userPassword;

    public function __construct($userEmail, $userPassword)
    {
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
    }

    public function getEmail()
    {
        return $this->userEmail;
    }

    public function getPassword()
    {
        return $this->userPassword;
    }
}