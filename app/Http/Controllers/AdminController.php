<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{

public function adminDashboard()
    {
        $totalUsers = User::whereNot('role', 'admin')->count();
        $citizenUsers = User::where('role', 'citizen')->count();
        $lguUsers = User::where('role', 'lgu')->count();

        // Fetch assistance counts by type
        $assistanceCounts = [
            'Burial' => Assistance::where('name', 'LIKE', '%burial%')->count(),
            'Food Supply' => Assistance::where('name', 'LIKE', '%food%')
                ->orWhere('name', 'LIKE', '%supply%')->count(),
            'Medical' => Assistance::where('name', 'LIKE', '%medical%')->count(),
            'Educational Assistance' => Assistance::where('name', 'LIKE', '%educational%')->count(),
            'Senior Citizen' => Assistance::where('name', 'LIKE', '%senior%')->count(),
            'Transportation' => Assistance::where('name', 'LIKE', '%transpo%')->count(),
        ];

        return Inertia::render('admin/Dashboard', [
            'totalUsers' => $totalUsers,
            'citizenUsers' => $citizenUsers,
            'lguUsers' => $lguUsers,
            'assistanceData' => [
                'labels' => array_keys($assistanceCounts),
                'counts' => array_values($assistanceCounts),
            ],
        ]);
    }

    public function getSystemHealth()
    {
        // Check database connection
        $databaseStatus = 'operational';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $databaseStatus = 'error';
        }

        // Check if there are any failed jobs (requires queue)
        $failedJobsCount = 0;
        try {
            $failedJobsCount = DB::table('failed_jobs')->count();
        } catch (\Exception $e) {
            // Table might not exist if not using queues
        }

        // Get pending tasks count (you can customize this based on your needs)
        $pendingTasks = 0;
        try {
            $pendingTasks = DB::table('jobs')->count();
        } catch (\Exception $e) {
            // Table might not exist if not using queues
        }

        return response()->json([
            'database_status' => $databaseStatus,
            'api_status' => 'healthy', // You can add actual API health checks here
            'failed_jobs' => $failedJobsCount,
            'pending_tasks' => $pendingTasks,
            'disk_usage' => $this->getDiskUsage(),
            'memory_usage' => $this->getMemoryUsage(),
        ]);
    }

    private function getDiskUsage()
    {
        $bytes = disk_free_space(".");
        $total = disk_total_space(".");
        $used = $total - $bytes;
        
        return [
            'used' => $used,
            'total' => $total,
            'percentage' => $total > 0 ? round(($used / $total) * 100, 1) : 0,
        ];
    }

    private function getMemoryUsage()
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        
        // Convert memory limit to bytes
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        
        return [
            'used' => $memoryUsage,
            'limit' => $memoryLimitBytes,
            'percentage' => $memoryLimitBytes > 0 ? round(($memoryUsage / $memoryLimitBytes) * 100, 1) : 0,
        ];
    }

    private function convertToBytes($value)
    {
        $unit = strtolower(substr($value, -1, 1));
        $value = (int) $value;
        
        switch ($unit) {
            case 'g':
                $value *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $value *= 1024 * 1024;
                break;
            case 'k':
                $value *= 1024;
                break;
        }
        
        return $value;
    }


    public function index(Request $request)
    {
        $query = User::query()
            ->select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
            ->whereNot('role', 'admin') // Exclude admin users
            ->orderBy('created_at', 'desc'); // Order by newest first

        // Apply search filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . trim($request->name) . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . trim($request->email) . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return Inertia::render('admin/Users', [
            'users' => $users,
            'filters' => [
                'name' => $request->get('name', ''),
                'email' => $request->get('email', ''),
                'role' => $request->get('role', ''),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['citizen', 'lgu'])], // Restrict to citizen or lgu
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent editing admin users
        if ($user->role === 'admin') {
            return redirect()->route('admin.users')->with('error', 'Cannot edit admin users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['citizen', 'lgu'])], // Restrict to citizen or lgu
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

 public function users_destroy(User $user)
{
    // Prevent admins from deleting themselves
    if ($user->id === Auth::id()) {
        return back()->with('error', 'You cannot delete yourself.');
    }

    $user->delete();
    return back()->with('success', 'User deleted successfully!');
}

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent changing admin role
        if ($user->role === 'admin') {
            return redirect()->route('admin.users')->with('error', 'Cannot change admin user role.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['citizen', 'lgu'])], // Restrict to citizen or lgu
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users')->with('success', 'User role updated successfully.');
    }


    public function assistance_form()
    {
       
        return Inertia::render('admin/Assistance_form', [
           
        ]);
    }

    public function feedback()
    {
       
        return Inertia::render('admin/Feedback', [
           
        ]);
    }

    public function service_experience()
    {
       
        return Inertia::render('admin/Service_experience', [
           
        ]);
    }

    public function about()
    {
       
        return Inertia::render('admin/About', [
           
        ]);
    }

    public function assistance_create()
    {
        // You can add logic here to handle the creation of assistance
        return Inertia::render('admin/assistance/create', [
            // Pass any necessary data to the view
        ]);
    }

    // Store the new assistance
    public function assistance_store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'deadline' => 'required|date|after:now',
            'description' => 'nullable|string',
            'number_of_questions' => 'required|integer|min:0',
            'survey_instructions' => 'nullable|string',
            'target_audience' => 'nullable|string|max:255',
        ]);

        // Create the assistance
        Assistance::create([
            'user_id' => Auth::id(), // Assign the logged-in user's ID
            'name' => $validated['name'],
            'deadline' => $validated['deadline'],
            'description' => $validated['description'],
            'number_of_questions' => $validated['number_of_questions'],
            'survey_instructions' => $validated['survey_instructions'],
            'target_audience' => $validated['target_audience'],
        ]);

        // Redirect to a success page or assistance list with a success message
        return redirect()->route('admin.assistance_list')->with('success', 'Assistance created successfully!');
    }

    
   // Display the assistance list
    public function assistance_list()
    {
        $assistances = Assistance::with('user')
            ->select('id', 'name', 'user_id')
            ->latest()
            ->get()
            ->map(function ($assistance) {
                return [
                    'id' => $assistance->id,
                    'name' => $assistance->name,
                    'route' => route('admin.assistance.show', $assistance->id), // Dynamic route for each assistance
                    'color' => $this->getColorForAssistance($assistance->name), // Assign color based on name
                    'hoverColor' => $this->getHoverColorForAssistance($assistance->name), // Assign hover color
                ];
            });

        return Inertia::render('admin/Assistance_list', [
            'assistances' => $assistances,
        ]);
    }







    

    // Helper method to assign colors based on assistance name
    private function getColorForAssistance($name)
    {
        $name = strtolower($name);
        if (str_contains($name, 'burial')) {
            return 'bg-purple-700';
        } elseif (str_contains($name, 'food') || str_contains($name, 'supply')) {
            return 'bg-red-500';
        } elseif (str_contains($name, 'medical')) {
            return 'bg-orange-500';
        } elseif (str_contains($name, 'educational')) {
            return 'bg-purple-700';
        } elseif (str_contains($name, 'senior')) {
            return 'bg-pink-600';
        } elseif (str_contains($name, 'transportation') || str_contains($name, 'transpo')) {
            return 'bg-purple-900';
        }
        return 'bg-gray-500'; // Default color
    }

    // Helper method to assign hover colors
    private function getHoverColorForAssistance($name)
    {
        $name = strtolower($name);
        if (str_contains($name, 'burial')) {
            return 'hover:bg-purple-800';
        } elseif (str_contains($name, 'food') || str_contains($name, 'supply')) {
            return 'hover:bg-red-600';
        } elseif (str_contains($name, 'medical')) {
            return 'hover:bg-orange-600';
        } elseif (str_contains($name, 'educational')) {
            return 'hover:bg-purple-800';
        } elseif (str_contains($name, 'senior')) {
            return 'hover:bg-pink-700';
        } elseif (str_contains($name, 'transportation') || str_contains($name, 'transpo')) {
            return 'hover:bg-purple-950';
        }
        return 'hover:bg-gray-600'; // Default hover color
    }

    public function assistance_show(Assistance $assistance)
{
    return Inertia::render('admin/assistance/show', [
        'assistance' => [
            'id' => $assistance->id,
            'name' => $assistance->name,
            'deadline' => $assistance->deadline,
            'description' => $assistance->description,
            'number_of_questions' => $assistance->number_of_questions,
            'survey_instructions' => $assistance->survey_instructions,
            'target_audience' => $assistance->target_audience,
            'user' => $assistance->user ? $assistance->user->name : 'N/A',
        ],
    ]);
}


}