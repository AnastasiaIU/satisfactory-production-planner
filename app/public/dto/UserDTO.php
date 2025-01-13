<?php

/**
 * Data Transfer Object (DTO) for representing a user.
 */
class UserDTO
{
    private readonly string $id;
    private readonly string $email;
    private readonly string $password;

    public function __construct(string $id, string $email, string $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

}