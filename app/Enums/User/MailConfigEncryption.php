<?php

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class MailConfigEncryption extends Enum
{
    const TLS = 'tls';
    const SSL = 'ssl';
}
