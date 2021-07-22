<?php


namespace App\Services;


use App\Sales;

class PaymentReminderMessageService
{
    public function filter($salesId, $body)
    {
        $sales = Sales::findOrFail($salesId);
        $search = array(
            '{client_full_name}',
            '{client_first_name}',
            '{client_middle_name}',
            '{client_last_name}',
            '{username}',
            '{client_mobile_no}',
            '{client_email}',
            '{client_address}',
            '{sales_project}',
            '{sales_price}',
            '{sales_monthly_payment}',
            '{sales_model_unit}',
            '{sales_block_and_lot}',
        );

        $replace = array(
            ucfirst($sales->lead->fullname), //full name
            ucfirst($sales->lead->firstname), //first name
            ucfirst($sales->lead->middlename), //middle name
            ucfirst($sales->lead->lastname), //last name
            $sales->lead->username, // username
            $sales->lead->mobileNo, //mobile number
            $sales->lead->email, //email
            $sales->lead->address,//address
            ucfirst($sales->project->name),//project name
            number_format($sales->total_contract_price,2),//total contract price
            number_format($sales->payment_amount,2),//monthly payment amount
            ucfirst($sales->modelUnit->name),//model unit
            $sales->location,//phase, block, lot
        );

        $filtered = str_replace($search,$replace,$body);
        return $filtered;
    }
}