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
    public function getSalesOriginalData($id,$status, $changes = null)
    {
        $reservation_date = isset($changes['reservation_date'])?$changes['reservation_date']:'';
        $buyer = isset($changes['lead_id'])?$changes['lead_id']:'';
        $project = isset($changes['project_id'])?$changes['project_id']:'';
        $modeUnit = isset($changes['model_unit_id'])?$changes['model_unit_id']:'';
        $lot_area = isset($changes['lot_area'])?$changes['lot_area']:'';
        $floor_area = isset($changes['floor_area'])?$changes['floor_area']:'';
        $phase = isset($changes['phase'])?$changes['phase']:'';
        $block = isset($changes['block'])?$changes['block']:'';
        $lot = isset($changes['lot'])?$changes['lot']:'';
        $total_contract_price = isset($changes['total_contract_price'])?$changes['total_contract_price']:'';
        $discount = isset($changes['discount'])?$changes['discount']:'';
        $processing_fee = isset($changes['processing_fee'])?$changes['processing_fee']:'';
        $reservation_fee = isset($changes['reservation_fee'])?$changes['reservation_fee']:'';
        $equity = isset($changes['equity'])?$changes['equity']:'';
        $loanable_amount = isset($changes['loanable_amount'])?$changes['loanable_amount']:'';
        $financing = isset($changes['financing'])?$changes['financing']:'';
        $terms = isset($changes['terms'])?$changes['terms']:'';
        $details = isset($changes['details'])?$changes['details']:'';

        $sale = $this->getSalesById($id);
        $originalData = '<table class="table table-sm table-bordered table-hover">';
        $originalData .= '<tr><td>Reservation Date</td><td>'.$sale->reservation_date.'</td><td>'.$reservation_date.'</td></tr>';
        $originalData .= '<tr><td>Client Name</td><td>'.$sale->lead->fullname.'</td><td>'.$buyer.'</td></tr>';
        $originalData .= '<tr><td>Project</td><td>'.$sale->project->name.'</td><td>'.$project.'</td></tr>';
        $originalData .= '<tr><td>Model Unit</td><td>'.$sale->modelUnit->name.'</td><td>'.$modeUnit.'</td></tr>';
        $originalData .= '<tr><td>Lot Area</td><td>'.$sale->lot_area.'</td><td>'.$lot_area.'</td></tr>';
        $originalData .= '<tr><td>Floor Area</td><td>'.$sale->floor_area.'</td><td>'.$floor_area.'</td></tr>';
        $originalData .= '<tr><td>Phase</td><td>'.$sale->phase.'</td><td>'.$phase.'</td></tr>';
        $originalData .= '<tr><td>Block</td><td>'.$sale->block.'</td><td>'.$block.'</td></tr>';
        $originalData .= '<tr><td>Lot</td><td>'.$sale->lot.'</td><td>'.$lot.'</td></tr>';
        $originalData .= '<tr><td>Total Contract Price</td><td>'.number_format($sale->total_contract_price).'</td><td>'.$total_contract_price.'</td></tr>';
        $originalData .= '<tr><td>Discount</td><td>'.number_format($sale->discount).'</td><td>'.$discount.'</td></tr>';
        $originalData .= '<tr><td>Processing Fee</td><td>'.number_format($sale->processing_fee).'</td><td>'.$processing_fee.'</td></tr>';
        $originalData .= '<tr><td>Reservation Fee</td><td>'.number_format($sale->reservation_fee).'</td><td>'.$reservation_fee.'</td></tr>';
        $originalData .= '<tr><td>Equity</td><td>'.number_format($sale->equity).'</td><td>'.$equity.'</td></tr>';
        $originalData .= '<tr><td>Loanable Amount</td><td>'.number_format($sale->loanable_amount).'</td><td>'.$loanable_amount.'</td></tr>';
        $originalData .= '<tr><td>Financing</td><td>'.$sale->financing.'</td><td>'.$financing.'</td></tr>';
        $originalData .= '<tr><td>Terms</td><td>'.$sale->terms.'</td><td>'.$terms.'</td></tr>';
        $originalData .= '<tr><td>Details</td><td>'.$sale->details.'</td><td>'.$details.'</td></tr>';
        $originalData .= '<tr><td>Status</td><td>'.$sale->status.'</td><td>'.$status.'</td></tr>';
        $originalData .= '</table>';

        return $originalData;
    }


    /**
     * @since April 25, 2020
     * @author john kevin paunel
     * update the sales
     *
     * */
    public function updateSales($request, $id)
    {
        $sales = Sales::find($id);
        $sales->reservation_date = $request->edit_reservation_date;
        $sales->lead_id = $request->edit_buyer;
        $sales->project_id = $request->edit_project;
        $sales->model_unit_id = $request->edit_model_unit;
        $sales->lot_area = $request->edit_lot_area;
        $sales->floor_area = $request->edit_floor_area;
        $sales->phase = $request->edit_phase;
        $sales->block = $request->edit_block_number;
        $sales->lot = $request->edit_lot_number;
        $sales->total_contract_price = $request->edit_total_contract_price;
        $sales->discount = $request->edit_discount;
        $sales->processing_fee = $request->edit_processing_fee;
        $sales->reservation_fee = $request->edit_reservation_fee;
        $sales->equity = $request->edit_equity;
        $sales->loanable_amount = $request->edit_loanable_amount;
        $sales->financing = $request->edit_financing;
        $sales->terms = $request->edit_dp_terms;
        $sales->details = $request->edit_details;

        if($sales->isDirty())
        {
            $sales->save();
            return ['success' => true, 'message' => 'Sales Successfully Updated!', $sales];
        }

        return ['success' => false, 'message' => 'No Changes Occurred!', $sales];
    }

}
