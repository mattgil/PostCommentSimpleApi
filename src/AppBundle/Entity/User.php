<?php

namespace AppBundle\Entity;

class User
{
    private $id;

    private $email;

    private $name;

    private $surname;

    private $password;

    /**
     * User constructor.
     * @param string $email
     * @param string $name
     * @param string $surname
     * @param string $password
     */
    public function __construct(string $email, string $name, string $surname, string $password)
    {
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->password = password_hash($password, PASSWORD_BCRYPT) or $this->throwPasswordHashFailedException();
    }

    public function throwPasswordHashFailedException()
    {
        $class = get_class($this);
        throw new \Exception("Failed to create password hash in $class");
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
