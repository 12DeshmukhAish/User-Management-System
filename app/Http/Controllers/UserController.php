<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // ... keeping other methods same ...

    public function export()
    {
        $users = User::all();
        
        $fileName = 'users_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        
        $columns = ['Name', 'Email', 'Mobile No', 'Profile Picture'];
        
        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($users as $user) {
                $row = [
                    $user->name,
                    $user->email,
                    $user->mobile_no,
                    $user->profile_pic ?? 'No Image'
                ];
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}