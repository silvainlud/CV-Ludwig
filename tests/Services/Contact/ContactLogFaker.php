<?php

namespace App\Tests\Services\Contact;

use App\Entity\Main\ContactLog;
use DateTimeInterface;

class ContactLogFaker extends ContactLog
{
    public function __construct(string $ipAddress, DateTimeInterface $dateTime)
    {
        parent::__construct($ipAddress);
        $this->dateCreated = $dateTime;
    }
}
