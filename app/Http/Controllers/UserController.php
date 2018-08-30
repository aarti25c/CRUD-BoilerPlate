<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Exception;

class UserController extends Controller
{
      public function __construct(){
            $this->middleware('auth');            
        }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        try {
            $users = User::latest()->paginate(2);

            return view('users.index',compact('users'))
             ->with('i', (request()->input('page', 1) - 1) * 2);
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        try {
            return view('users.create');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            request()->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

            $request->merge([
                'password' => Hash::make('test'),
            ]);

        User::create($request->all());

        return redirect()->route('user.index')
                        ->with('success','User created successfully');

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user){
        try {
            return view('users.show',compact('user'));

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user){
        try {

            return view('users.edit',compact('user'));

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user){
        try {
            request()->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        $user->update($request->all());

        return redirect()->route('user.index')
                        ->with('success','Member updated successfully');

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try {
            User::destroy($id);
            return redirect()->route('user.index')
                        ->with('success','Member deleted successfully');

        } catch (Exception $e) {
           throw $e; 
        }
    }
}
