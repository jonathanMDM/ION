<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Asset;
use App\Models\Maintenance;
use App\Mail\WeeklyDigest;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendWeeklyDigest extends Command
{
    protected $signature = 'reports:send-weekly-digest';
    protected $description = 'Send weekly digest emails to users who have enabled this preference';

    public function handle()
    {
        $this->info('Sending weekly digest emails...');

        // Get users with weekly digest enabled
        $users = User::where('is_active', true)
            ->whereNotNull('company_id')
            ->get()
            ->filter(function ($user) {
                return isset($user->preferences['notifications']['weekly_digest']) 
                    && $user->preferences['notifications']['weekly_digest'] === true;
            });

        if ($users->isEmpty()) {
            $this->info('No users have weekly digest enabled.');
            return 0;
        }

        $totalEmails = 0;
        $weekStart = Carbon::now()->startOfWeek()->format('d/m/Y');
        $weekEnd = Carbon::now()->endOfWeek()->format('d/m/Y');

        foreach ($users as $user) {
            try {
                $company = $user->company;
                
                // Calculate statistics
                $stats = [
                    'total_assets' => Asset::where('company_id', $company->id)->sum('quantity'),
                    'new_assets' => Asset::where('company_id', $company->id)
                        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->count(),
                    'low_stock_count' => Asset::where('company_id', $company->id)
                        ->lowStock()
                        ->count(),
                    'maintenances_count' => Maintenance::whereHas('asset', function($query) use ($company) {
                            $query->where('company_id', $company->id);
                        })
                        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->count(),
                ];

                // Get recent assets
                $recentAssets = Asset::where('company_id', $company->id)
                    ->with(['subcategory.category'])
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->latest()
                    ->take(5)
                    ->get();

                // Get low stock assets
                $lowStockAssets = Asset::where('company_id', $company->id)
                    ->with(['subcategory.category'])
                    ->lowStock()
                    ->take(5)
                    ->get();

                // Get upcoming maintenances
                $upcomingMaintenances = Maintenance::whereHas('asset', function($query) use ($company) {
                        $query->where('company_id', $company->id);
                    })
                    ->with('asset')
                    ->where('date', '>=', Carbon::now())
                    ->where('date', '<=', Carbon::now()->addDays(7))
                    ->orderBy('date')
                    ->take(5)
                    ->get();

                // Send email
                Mail::to($user->email)->send(new WeeklyDigest(
                    $user->name,
                    $company->name,
                    $weekStart,
                    $weekEnd,
                    $stats,
                    $recentAssets,
                    $lowStockAssets,
                    $upcomingMaintenances
                ));

                $this->info("✉️ Email sent to {$user->email}");
                $totalEmails++;

            } catch (\Exception $e) {
                $this->error("✗ Failed to send email to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("✅ Process completed! Sent {$totalEmails} weekly digest email(s).");
        return 0;
    }
}
