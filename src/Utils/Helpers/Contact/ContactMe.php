<?php

namespace App\Utils\Helpers\Contact;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints as Assert;

class ContactMe
{
    #[NotBlank]
    #[Length(max: 32)]
    protected string $name;
    #[NotBlank]
    #[\Symfony\Component\Validator\Constraints\Email]
    #[Length(max: 32)]
    protected string $email;
    #[Length(max: 1000, min: 15)]
    #[NotBlank]
    protected string $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function makeEmail(string $to): Email
    {
        return (new Email())
            ->from(new Address($this->email, $this->name))
            ->to($to)
            ->subject('Un email de contact (silvain.eu) de ' . $this->name)
            ->text($this->message);
    }
}
