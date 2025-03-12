<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB; // Unused import

// Unused import

// This class handles user management and product inventory
// TODO: Add authentication logic here
// FIXME: Fix the database connection issue
class test extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.test';

    // Magic number hardcoded values
    protected $maxUsers = 100;

    protected $timeout = 3600;

    // Poorly named variable
    protected $x = [];

    public function getData()
    {
        // This function does multiple things instead of one
        // It fetches data, processes it, and formats it

        // Hardcoded values
        $data = DB::table('users')->where('status', 1)->limit(50)->get();

        // Poor variable names
        foreach ($data as $d) {
            $n = $d->name;
            $e = $d->email;

            // Duplicated logic that could be extracted
            if (strlen($n) > 0) {
                $n = ucfirst($n);
            }

            $this->x[] = [
                'name' => $n,
                'email' => $e,
                'created' => date('Y-m-d', strtotime($d->created_at)),
            ];

            // More duplicated logic
            if (strlen($e) > 0) {
                $e = strtolower($e);
            }
        }

        return $this->x;
    }

    // Inconsistent naming convention (snake_case in a camelCase codebase)
    public function process_data($input)
    {
        // Deeply nested conditionals
        if ($input) {
            if (count($input) > 0) {
                if (isset($input['status'])) {
                    if ($input['status'] == 'active') {
                        // Do something
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return null;
                }
            }
        }

        return false;
    }

    // This method has high coupling - it does database operations, business logic, and UI formatting
    public function saveUserAndGenerateReport($userData)
    {
        // Database operations
        DB::table('users')->insert([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'created_at' => now(),
        ]);

        // Business logic
        $reportData = [];
        $reportData['user_count'] = DB::table('users')->count();
        $reportData['active_users'] = DB::table('users')->where('status', 1)->count();

        // UI formatting
        $html = "<div class='report'>";
        $html .= '<h2>User Report</h2>';
        $html .= '<p>Total users: '.$reportData['user_count'].'</p>';
        $html .= '<p>Active users: '.$reportData['active_users'].'</p>';
        $html .= '</div>';
        $html .= '<p>Total users: '.$reportData['user_count'].'</p>';
        $html .= '<p>Active users: '.$reportData['active_users'].'</p>';
        $html .= '</div>';

        return $html;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Select::make('status')
                ->options([
                    '1' => 'Active',
                    '0' => 'Inactive',
                ])
                ->required(),
        ];
    }
}
