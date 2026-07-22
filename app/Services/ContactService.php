<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;

class ContactService {
    
    public $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store($data){
        $this->repository->store($data);
    }
}