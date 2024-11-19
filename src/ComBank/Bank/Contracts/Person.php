<?php namespace ComBank\Bank;
class    Person {
    // Private properties
    private string $name;
    private string $idCard;
    private string $email;

    // Constructor to initialize the properties
    public function __construct(string $name, string $idCard, string $email) {
        $this->name = $name;
        $this->idCard = $idCard;
        $this->email = $email;
    }

    // Getter and setter for 'name'
    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    // Getter and setter for 'idCard'
    public function getIdCard(): string {
        return $this->idCard;
    }

    public function setIdCard(string $idCard): void {
        $this->idCard = $idCard;
    }

    // Getter and setter for 'email'
    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }
}
