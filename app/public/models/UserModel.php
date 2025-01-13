<?php

require_once(__DIR__ . '/BaseModel.php');
require_once (__DIR__ . '/../dto/UserDTO.php');

/**
 * UserModel class extends BaseModel to interact with the USER entity in the database.
 */
class UserModel extends BaseModel
{
    /**
     * Retrieves a user by their email.
     *
     * @param string $email The email of the user to retrieve.
     * @return UserDTO|null The data transfer object representing the user or null if the user is not found.
     */
    public function getUser(string $email): ?UserDTO
    {
        $query = self::$pdo->prepare(
            'SELECT id, email, password
                    FROM USER
                    WHERE email = :email'
        );
        $query->execute(['email' => $email]);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            return null;
        }

        return new UserDTO(
            $item['id'],
            $item['email'],
            $item['password']
        );
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $email The email of the user to create.
     * @param string $password The password of the user to create.
     */
    public function createUser(string $email, string $password): void {
        $query = self::$pdo->prepare('INSERT INTO USER (email, password) VALUES (:email, :password)');

        $query->bindParam(":email", $email);
        $query->bindParam(":password", $password);

        $query->execute();
        $query->closeCursor();
    }
}