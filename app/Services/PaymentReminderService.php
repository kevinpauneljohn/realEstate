<?php


namespace App\Services;


use App\PaymentReminder;
use App\Repositories\SalesRepository;
use Carbon\Carbon;

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
            );
        }
        return PaymentReminder::insert($dueDates);
    }
}