<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Order;
use App\Models\Sermon;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'users'              => User::count(),
            'sermons'            => Sermon::count(),
            'subscribers'        => Subscriber::where('status', 'active')->count(),
            'pending_testimonials' => Testimonial::where('status', 'pending')->count(),
            'total_donations'    => Donation::where('status', 'verified')->sum('amount'),
            'orders'             => Order::count(),
        ]);
    }
}