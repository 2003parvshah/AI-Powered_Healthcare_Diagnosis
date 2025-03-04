<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\helper\mail;

class SendAppointmentReminder extends Command
{
    protected $signature = 'send:appointment-reminder';
    protected $description = 'Send appointment reminders to users one day before their appointment';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Fetch users who have an appointment tomorrow
        $appointments = DB::table('appointments as a')
            ->join('health_issues as hi', 'a.health_issues_id', '=', 'hi.id')
            ->join('users as u', 'hi.patient_id', '=', 'u.id')
            ->whereDate('a.appointment_date', $tomorrow)
            ->select('u.email', 'a.appointment_date', 'a.')
            ->get();

        if ($appointments->isNotEmpty()) {
            foreach ($appointments as $appointment) {
                $to = $appointment->email;
                $subject = "Appointment Reminder - " . Carbon::parse($appointment->appointment_date)->format('d M Y');
                $message = "
                    <p>Dear Patient,</p>
                    <p>This is a reminder that you have an appointment scheduled for <strong>" . Carbon::parse($appointment->appointment_date)->format('d M Y, h:i A') . "</strong>.</p>
                    <p>Please be on time.</p>
                    <p>Thank you!</p>
                ";

                // Send email
                $status = mail::sendmail($to, $subject, $message);

                $this->info("Reminder sent to $to: $status");
            }
        } else {
            $this->info('No appointments for tomorrow.');
        }
    }
}
