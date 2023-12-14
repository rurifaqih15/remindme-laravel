<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use Carbon\Carbon;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get reminders that need to be sent
        $reminders = Reminder::
            where('remind_at', '==', Carbon::now()->timestamp)
            ->get();
        foreach ($reminders as $reminder) {
            $reminder->user->notify(new ReminderNotification($reminder));
        }
    }
}
