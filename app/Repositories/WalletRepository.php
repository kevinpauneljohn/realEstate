<?php


namespace App\Repositories;


use App\Events\SendMoneyEvent;

class WalletRepository
{
    public function setMoney($receiver,$sender,$amount,$description,$is_incentives,$for_release,$category,$status)
    {
        $data = array(
            'user_id' => $receiver,
            'amount'    => $amount,
            'details'   => array(
                'sender'    => $sender,
                'description'   => $description,
                'incentives'    => $is_incentives,
                'for_release'   => $for_release
            ),
            'category'  => $category,
            'status'    => $status
        );
        event(new SendMoneyEvent((object)$data));
    }
}
