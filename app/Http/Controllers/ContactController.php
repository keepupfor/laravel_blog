<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMeRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
   public function showForm()
   {
       return view('blog.contact');
   }
   public function sendContactInfo(ContactMeRequest $request)
   {
       $data=$request->only('name','email','phone');
       $data['messageLines']=explode("\n",$request->get('message'));
       Mail::queue('emails.contact', $data, function ($message) use ($data) {
           $message->subject('来自: '.$data['name'].'的来信')->to(config('blog.contact_email'))
              ->replyTo($data['email']);
       });
       return back()->withSuccess("感谢您的来信，信息已发送");
   }
}
