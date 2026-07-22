<?php

namespace App\Services;

use App\Mail\AdminContactNotification;
use App\Mail\UserContactCopy;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Mail;

class ContactService {
    
    public $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store($data){
        
        // Create new comment
        $this->repository->store($data);

        // Send mail to admin
        Mail::to(env('ADMIN_EMAIL'))->send(new AdminContactNotification($data));

        // Send copy to user
        Mail::to($data['email'])->send(new UserContactCopy($data));

    }
}