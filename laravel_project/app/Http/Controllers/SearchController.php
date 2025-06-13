<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User; // Assuming User model is for staff

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->input('search_text');

        if (empty($searchTerm)) {
            // return redirect()->back()->with('error', 'Search term cannot be empty.');
            return response("Search term cannot be empty. Redirect back with error.");
        }

        // Placeholder: Search Students
        // In a real scenario, consider using Laravel Scout or more advanced full-text search
        $students = Student::where('firstname', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('lastname', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('admission_no', 'LIKE', "%{$searchTerm}%")
                            // Add other relevant student fields to search
                            ->limit(10) // Example limit
                            ->get();

        // Placeholder: Search Staff (Users)
        $staff = User::where('name', 'LIKE', "%{$searchTerm}%")
                       ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                       ->orWhere('employee_id', 'LIKE', "%{$searchTerm}%")
                       // Add other relevant staff fields
                       ->limit(10) // Example limit
                       ->get();

        // Placeholder: Search Custom Fields (more complex, involves joins or separate queries)
        // $customFieldResults = [];

        $results = [
            'searchTerm' => $searchTerm,
            'students' => $students,
            'staff' => $staff,
            // 'customFieldResults' => $customFieldResults,
        ];

        // The actual view will be created in a later step
        // return view('search.results', $results);
        return response("Search Results for: " . e($searchTerm) . "<pre>" . print_r($results, true) . "</pre> <p>View will be search.results</p>");
    }
}
