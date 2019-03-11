<?php
class User
    {
        public $Email;
        public $Password;
        public function __construct($Email, $Password)
        {
            $this->Email = $Email;
            $this->Password = $Password;
        }
    }