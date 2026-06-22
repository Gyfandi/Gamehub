<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'buyer')->count();
        $totalGames = Game::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total');

        // Top Selling Games (Top 5)
        $topSelling = TransactionDetail::select('game_id', DB::raw('COUNT(*) as sales_count'), DB::raw('SUM(price) as revenue'))
            ->groupBy('game_id')
            ->orderByDesc('sales_count')
            ->take(5)
            ->with('game')
            ->get();

        // Monthly Sales (Current year, grouped by month)
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $monthExpr = "strftime('%m', created_at)";
        } elseif ($driver === 'oracle' || $driver === 'oci') {
            $monthExpr = "TO_CHAR(created_at, 'MM')";
        } else {
            $monthExpr = "EXTRACT(MONTH FROM created_at)";
        }

        $monthlySales = Transaction::select(
                DB::raw("$monthExpr as month"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw($monthExpr))
            ->orderBy(DB::raw($monthExpr))
            ->get()
            ->keyBy(function($item) {
                // Ensure month format is always 2 digits (e.g. "01")
                return str_pad(intval($item->month), 2, '0', STR_PAD_LEFT);
            });

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $chartLabels = [];
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = str_pad($m, 2, '0', STR_PAD_LEFT);
            $chartLabels[] = $months[$m - 1];
            $chartData[] = $monthlySales->has($key) ? $monthlySales[$key]->revenue : 0;
        }

        // Recent Activity Logs
        $activityLogs = ActivityLog::with('user')->orderByDesc('created_at')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalGames', 'totalTransactions', 'totalRevenue',
            'topSelling', 'chartLabels', 'chartData', 'activityLogs'
        ));
    }
}
