<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            return view('dashboard', [
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to load dashboard: ' . $e->getMessage()]);
        }
    }

    /**
     * Show all users (Admin only)
     */
    public function users(Request $request)
    {
        try {
            // Get paginated users (10 per page)
            $users = User::orderBy('created_at', 'desc')->paginate(10);

            // Get counts for stats
            $totalUsers = User::count();
            $adminCount = User::where('role', 'admin')->count();
            $userCount  = User::where('role', 'user')->count();

            return view('admin.users', [
                'users'      => $users,
                'adminCount' => $adminCount,
                'userCount'  => $userCount,
                'totalUsers' => $totalUsers,
            ]);

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to load users: ' . $e->getMessage()]);
        }
    }
}
