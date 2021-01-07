<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'username' => 'required',
        'first_pass' => 'required',
        'second_pass' => 'required',
    ];

    protected $rules_update = [
        'name' => 'required',
        'email' => 'required|email',
        'username' => 'required',

    ];

    protected $messages = [
        'name.required' => 'Introduceti numele utilizatorului!',
        'email.required' => 'Introduceti o adresa de email!',
        'email.email' => 'Introduceti o adresa de email valida!',
        'username.required' => 'Introduceti un nume de utilizator!',
        'first_pass.required' => 'Introduceti o parola!',
        'second_pass.required' => 'Introduceti verificarea parolei!',
    ];

    protected $messages_update = [
        'name.required' => 'Introduceti numele utilizatorului!',
        'email.required' => 'Introduceti o adresa de email!',
        'email.email' => 'Introduceti o adresa de email valida!',
        'username.required' => 'Introduceti un nume de utilizator!',
    ];

    /**
     * Display the users index page
     *
     * @return view
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Add a new user to the database
     *
     * @param Request $request
     * @return back
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            if ($request->first_pass === $request->second_pass) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->username = $request->username;
                $user->password = bcrypt($request->first_pass);
                $user->save();

                return back()->with('success', 'Utilizatorul a fost adaugat cu succes!');
            }
        }

        return back(500)->with('error', 'The passwords must match');
    }

    /**
     * Get all the users in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $users = User::all();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('actions', function ($users) {
                return view('users.partials.actions', ['users' => $users]);
            })
            ->make(true);
    }

    /**
     * Fetch the data from a single user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $user = User::find($request->id);
        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $user]));
    }

    /**
     * Update a user data
     *
     * @return redirect
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules_update, $this->messages_update);

        $user = User::find($id);

        if ($validator->passes()) {
            if ($request->first_pass != null && $request->second_pass != null) {
                if ($request->first_pass === $request->second_pass) {
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->username = $request->username;
                    $user->password = bcrypt($request->first_pass);
                    $user->update();

                    return back()->with('success', 'Datele utilizatorului au fost editate!');
                }
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->update();

            return back()->with('success', 'Datele utilizatorului au fost editate!');
        }

        return back()->with('error', 'Nu au fost completate toate datele!');
    }
}
