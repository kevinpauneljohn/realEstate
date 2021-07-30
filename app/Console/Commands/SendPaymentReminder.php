<?php

namespace App\Console\Commands;

use App\Mail\MyTestMail;
use App\PaymentReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Client Payment Reminder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $month = now()->month; /// get current month which will be use to retrieve reminders of the month

        foreach (PaymentReminder::whereMonth('schedule', $month)->where('completed',false)->get() as $reminder)
        {
//            $clientEmail = $reminder->sales->lead->email;
            $clientEmail = $reminder->sales !== null ? $reminder->sales->lead->email : null;
            if(today()->diffInDays($reminder->scedule,false) === 5)
            {
                //this will remind the client of their payment 5 days before their due date
                if($clientEmail !== null)
                {
                    Mail::to($clientEmail)->bcc($reminder->sales->user->email)->send(new MyTestMail($reminder));
                }
            }elseif (today()->diffInDays($reminder->schedule, false) === 1){
                //this will remind the client of their payment 1 day before their due date
                if($clientEmail !== null)
                {
                    Mail::to($clientEmail)->bcc($reminder->sales->user->email)->send(new MyTestMail($reminder));
                }
            }elseif (today()->diffInDays($reminder->schedule, false) === 0){
                //this will remind the client of their payment today
                if($clientEmail !== null)
                {
                    Mail::to($clientEmail)->bcc($reminder->sales->user->email)->send(new MyTestMail($reminder));
                }

                if(today()->format('Y-m-d') === $reminder->schedule)
                {
                    ///if the current date is matched to the payment schedule it will update the completed column to true
                    PaymentReminder::where('schedule',today()->format('Y-m-d'))->update(['completed' => true]);
                }
            }
        }
        return true;
    }
}
