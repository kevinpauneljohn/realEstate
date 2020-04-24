<?php


namespace App\Repositories;


use App\Sales;

class SalesRepository
{
    /**
     * @since April 23, 2020
     * @author john kevin paunel
     * get sales object by id
     * @param int $id
     * @return object
     * */
    public function getSalesById($id)
    {
        return Sales::findOrFail($id);
    }

    /**
     * @since April 23, 2020
     * @author john kevin paunel
     * get the seller full name by id
     * @param int $id
     * @return object
     * */
    public function getSaleUserById($id)
    {
        return $this->getSalesById($id)->user;
    }


    /**
     * @since April 23, 2020
     * @author john kevin paunel
     * get all the sales object with seller full name
     * @param int $id
     * @return object
     * */
    public function getUserSaleCollectionBySaleId($id)
    {
        $sale = collect(['sale' => $this->getSalesById($id)]);
        $merged  = $sale->merge([
            'fullName' => $this->getSaleUserById($id)->fullname,
            'role' => $this->getSaleUserById($id)->getRoleNames(),
        ]);
        return $merged->all();
    }

    /**
     * @since April 04, 2020
     * @author john kevin paunel
     * create sales original data template
     * @param int $id
     * @return string
     * */
    public function getSalesOriginalData($id,$status)
    {
        $sale = $this->getSalesById($id);
        $originalData = '<table class="table table-sm table-bordered table-hover">';
        $originalData .= '<tr><td>Client Name</td><td>'.$sale->lead->fullname.'</td><td></td></tr>';
        $originalData .= '<tr><td>Project</td><td>'.$sale->project->name.'</td><td></td></tr>';
        $originalData .= '<tr><td>Model Unit</td><td>'.$sale->modelUnit->name.'</td><td></td></tr>';
        $originalData .= '<tr><td>Total Contract Price</td><td>'.number_format($sale->total_contract_price).'</td><td></td></tr>';
        $originalData .= '<tr><td>Discount</td><td>'.number_format($sale->discount).'</td><td></td></tr>';
        $originalData .= '<tr><td>Processing Fee</td><td>'.number_format($sale->processing_fee).'</td><td></td></tr>';
        $originalData .= '<tr><td>Reservation Fee</td><td>'.number_format($sale->reservation_fee).'</td><td></td></tr>';
        $originalData .= '<tr><td>Equity</td><td>'.number_format($sale->equity).'</td><td></td></tr>';
        $originalData .= '<tr><td>Loanable Amount</td><td>'.number_format($sale->loanable_amount).'</td><td></td></tr>';
        $originalData .= '<tr><td>Financing</td><td>'.$sale->financing.'</td><td></td></tr>';
        $originalData .= '<tr><td>Terms</td><td>'.$sale->terms.'</td><td></td></tr>';
        $originalData .= '<tr><td>Details</td><td>'.$sale->details.'</td><td></td></tr>';
        $originalData .= '<tr><td>Status</td><td>'.$sale->status.'</td><td>'.$status.'</td></tr>';
        $originalData .= '</table>';

        return $originalData;
    }

}
