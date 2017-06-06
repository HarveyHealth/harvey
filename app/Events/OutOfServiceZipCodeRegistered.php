<?php

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OutOfServiceZipCodeRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $request;
    public $notes = "Zip code is not serviceable.";
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
}
