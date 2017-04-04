<?php

namespace App\Http\Interfaces;

interface Mailable
{
    public function emailVerificationToken();
    public function emailVerificationURL();
}
