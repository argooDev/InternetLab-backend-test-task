<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public $service;
    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    public function store(StoreRequest $req) {
        $data = $req->validated();

        $this->service->store($data);
    }
}
