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
    public function getSalesOriginalData($id)
    {
        $sale = $this->getSalesById($id);
        $originalData = '<table class="table table-sm">';
        $originalData .= '<tr><td>Client Name</td><td>'.$sale->lead->fullname.'</td></tr>';
        $originalData .= '<tr><td>Project</td><td>'.$sale->project->name.'</td></tr>';
        $originalData .= '<tr><td>Model Unit</td><td>'.$sale->modelUnit->name.'</td></tr>';
        $originalData .= '<tr><td>Total Contract Price</td><td>'.$sale->total_contract_price.'</td></tr>';
        $originalData .= '<tr><td>Discount</td><td>'.$sale->discount.'</td></tr>';
        $originalData .= '<tr><td>Processing Fee</td><td>'.$sale->processing_fee.'</td></tr>';
        $originalData .= '<tr><td>Reservation Fee</td><td>'.$sale->reservation_fee.'</td></tr>';
        $originalData .= '<tr><td>Equity</td><td>'.$sale->equity.'</td></tr>';
        $originalData .= '<tr><td>Loanable Amount</td><td>'.$sale->loanable_amount.'</td></tr>';
        $originalData .= '<tr><td>Financing</td><td>'.$sale->financing.'</td></tr>';
        $originalData .= '<tr><td>Terms</td><td>'.$sale->terms.'</td></tr>';
        $originalData .= '<tr><td>Details</td><td>'.$sale->details.'</td></tr>';
        $originalData .= '<tr><td>Status</td><td>'.$sale->status.'</td></tr>';
        $originalData .= '</table>';

        return $originalData;
    }

}
