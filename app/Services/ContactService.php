<?php

namespace App\Services;

use App\Mail\AdminContactNotification;
use App\Mail\UserContactCopy;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Mail;

class ContactService {
    
    protected ContactRepository $repository;
    protected AiService $ai;


    public function __construct(ContactRepository $repository, AiService $ai)
    {
        $this->ai = $ai;
        $this->repository = $repository;
    }

    public function store(array $data){
        
        // Analyze comment
        $sense = $this->ai->analyzeSentiment($data['comment']);

        // Generate response
        $aiResponse = $this->ai->generateResponse($data['comment']);


        // Create new comment
        $this->repository->store(array_merge($data, [
            'sense'=>$sense,
            'ai_response'=>$aiResponse
        ]));

        // Send mail to admin
        Mail::to(env('ADMIN_EMAIL'))->send(new AdminContactNotification($data, $sense, $aiResponse));

        // Send copy to user
        Mail::to($data['email'])->send(new UserContactCopy($data, $sense, $aiResponse));

    }
}