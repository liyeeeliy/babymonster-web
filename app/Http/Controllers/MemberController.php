<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $members = Member::active()->get();
        return view('members.index', compact('members'));
    }

    public function show(Member $member): View
    {
        $member->load('fans');
        return view('members.show', compact('member'));
    }
}
