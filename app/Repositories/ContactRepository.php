<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository {

    public function store($data){
        Contact::create($data);
    }
}