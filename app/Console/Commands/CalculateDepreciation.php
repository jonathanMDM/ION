<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;

class CalculateDepreciation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:calculate-depreciation {--company= : Calculate only for specific company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and update depreciation for all assets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting depreciation calculation...');

        $query = Asset::where('depreciation_method', '!=', 'none')
            ->whereNotNull('depreciation_start_date')
            ->whereNotNull('purchase_price');

        if ($this->option('company')) {
            $query->where('company_id', $this->option('company'));
        }

        $assets = $query->get();
        $count = $assets->count();

        if ($count === 0) {
            $this->warn('No assets found for depreciation calculation.');
            return 0;
        }

        $this->info("Found {$count} assets to process.");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $updated = 0;
        $errors = 0;

        foreach ($assets as $asset) {
            try {
                $oldDepreciation = $asset->accumulated_depreciation;
                $asset->updateDepreciation();
                $newDepreciation = $asset->accumulated_depreciation;

                if ($oldDepreciation != $newDepreciation) {
                    $updated++;
                }

                $bar->advance();
            } catch (\Exception $e) {
                $errors++;
                $this->error("\nError processing asset {$asset->id}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Depreciation calculation completed!");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Assets Processed', $count],
                ['Assets Updated', $updated],
                ['Errors', $errors],
            ]
        );

        return 0;
    }
}
