<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get the dashboard view.
     *
     * @return  mixed
     */
    public function getView()
    {
        return view('dashboard', [
            'title' => 'Dashboard',
        ]);
    }
}
