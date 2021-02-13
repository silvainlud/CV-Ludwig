<?php

namespace App\Services\Contact;

use App\Utils\Helpers\Contact\ContactMe;

interface ContactMeFactoryInterface
{
    public function canSendMessage(): bool;

    public function sendMessage(ContactMe $contactMe): void;
}
