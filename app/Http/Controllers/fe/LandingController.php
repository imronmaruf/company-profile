<?php

namespace App\Http\Controllers\fe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('fe.index');
    }


    public function contactIndex()
    {
        return view('fe.pages.contact.index');
    }

    public function aboutIndex()
    {
        return view('fe.pages.about.index');
    }

    public function serviceIndex()
    {
        return view('fe.pages.service.index');
    }

    public function testimonialIndex()
    {
        return view('fe.pages.testimonial.index');
    }

    public function blogIndex()
    {
        return view('fe.pages.blog.index');
    }
}
