<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    # Get all users
    public function index() {
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        // withTrashed() - include the soft deleted records in query result
        return view('admin.users.index')->with('all_users', $all_users);
    }

    # deactivate a user
    public function deactivate($id) {
        $this->user->destroy($id);
        return redirect()->back();
    }

    # activate a user
    public function activate($id) {
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        // onlyTrashed() - retrieves soft delete records only
        // restore() - This will "un-delete" a soft deleted model. This will set "deleted_at" column to null
        return redirect()->back();
    }
}
