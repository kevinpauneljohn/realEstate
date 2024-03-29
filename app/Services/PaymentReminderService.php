<?php


namespace App\Services;


use App\PaymentReminder;
use App\Repositories\SalesRepository;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PaymentReminderService
{
    private $sales;
    public function __construct(SalesRepository $salesRepository)
    {
        $this->sales = $salesRepository;
    }

    public function viewSalesReminder($sales_id)
    {
        return PaymentReminder::where('sales_id',$sales_id);
    }

    public function viewAllSalesReminderOfCurrentUser($userId)
    {
        return PaymentReminder::whereMonth('schedule',now()->format('m'))
            ->whereYear('schedule',now()->format('Y'))
            ->whereIn('sales_id',collect($this->sales->getSalesByUser($userId)->get())->pluck('id'))
            ->get();
    }

    /**
     * check if there are existing payment reminders
     * @param $sales_id
     * @return bool
     */
    public function checkIfSalesReminderExists($sales_id)
    {
        if($this->viewSalesReminder($sales_id)->count() > 0)
        {
            return true;
        }
        return false;
    }

    public function removePaymentReminder($sales_id)
    {
        $this->viewSalesReminder($sales_id)->delete();
    }

    /**
     * save payment reminder to the database
     * @param $sales_id
     * @param $firstPayment
     * @param $amount
     * @return mixed
     */
    public function savePaymentSchedule($sales_id, $firstPayment, $amount)
    {
        if($this->checkIfSalesReminderExists($sales_id) === true)
        {
            $this->removePaymentReminder($sales_id);
        }
        $terms = $this->sales->viewSale($sales_id)->terms;
        $dueDates = array();

        $date = explode("-",$firstPayment);

        for ($month = 0; $month < $terms; $month++)
        {
            $dueDates[$month] = array(
                'sales_id' => $sales_id,
                'completed' => false,
                'schedule' => Carbon::create($date[0], $date[1], $date[2], 0, 0, 0)->addMonthsNoOverflow($month)->format('Y-m-d'),
                'amount' => $amount !== null ? $amount : 0.00,
                'created_at' => now(),
                'updated_at' => now()
            );
        }
        PaymentReminder::insert($dueDates);
        return $this->viewSalesReminder($sales_id)->get();
    }


    /**
     * update the amount due
     * @param $amountId
     * @param $amount
     * @return mixed
     */
    public function updatePaymentDueAmount($amountId, $amount)
    {
        $paymentReminder = PaymentReminder::find($amountId);
        $paymentReminder->amount = $amount;
        return $paymentReminder->save();
    }


    /**
     * this method will get all the schedule of payment
     * @param $sales_id
     * @param $firstPayment
     * @return array
     */
    public function scheduleFormatter($sales_id, $firstPayment)
    {
        $dueDates = array();

        $terms = $this->sales->viewSale($sales_id)->terms;
        $date = explode("-",$firstPayment);
        for ($month = 0; $month < $terms; $month++)
        {
            $dueDates[$month] = Carbon::create($date[0], $date[1], $date[2], 0, 0, 0)->addMonthsNoOverflow($month)->format('Y-m-d');
        }

        return $dueDates;
    }

    public function paymentRemindersThisMonth($userId)
    {
        return DataTables::of($this->viewAllSalesReminderOfCurrentUser($userId))
            ->editColumn('schedule',function($payment){
                return Carbon::create($payment->schedule)->format('F-d-Y');
            })
            ->editColumn('amount',function($payment){
                return number_format($payment->amount,2);
            })
            ->addColumn('client',function($payment){
                return '<a href="'.route("leads.show",["lead" => $payment->sales->lead_id]).'">'.$payment->sales->lead->fullname.'</a>';
            })
            ->addColumn('project',function($payment){
                return $payment->sales->project->name;
            })
            ->addColumn('modelUnit',function($payment){
                return $payment->sales->modelUnit->name;
            })
            ->addColumn('blk_and_lot',function($payment){
                return "Phase: ".$payment->sales->phase." Block: ".$payment->sales->block." Lot: ".$payment->sales->lot;
            })
            ->setRowClass(function($payment){
                $schedule = Carbon::create($payment->schedule)->format('m-d-Y');
                $dateNow = now()->format('m-d-Y');
                if($schedule === $dateNow){
                    return "due-date-now";
                }elseif (today()->diffInDays($payment->schedule,false) === 1){
                    return "due-date-1-day";
                }
                elseif (today()->diffInDays($payment->schedule,false) < 5 && today()->diffInDays($payment->schedule,false) > 1){
                    return "due-date-5-days";
                }elseif (today()->diffInDays($payment->schedule,false) < 0)
                {
                    return "due-date-finished";
                }
                return "";
            })
            ->rawColumns(['client'])
            ->make(true);
    }
}