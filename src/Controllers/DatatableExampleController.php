<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exist404\DatatableCruds\Facades\DatatableCruds;

class DatatableExampleController extends Controller
{
    public function index()
    {
        return DatatableCruds::setModel(User::class)->fillColumns()->fillInputs()->render();
    }
    public function store(Request $request)
    {
        User::create($request->all());
        return [
            'toast-message' => 'New User Has Been Added Successfully.',
            'toast-type' => 'success',
        ];
    }

    public function update($id, Request $request)
    {
        User::where($request->findBy, $id)->first()->update($request->all());
        return [
            'toast-message' => 'User Has Been Updated Successfully.',
            'toast-type' => 'success',
        ];
    }

    public function delete($id, Request $request)
    {
        User::where($request->findBy, $id)->first()->delete();
        return [
            'toast-message' => 'User Has Been Deleted Successfully.',
            'toast-type' => 'success',
        ];
    }
}
