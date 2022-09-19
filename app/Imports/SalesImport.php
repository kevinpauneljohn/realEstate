<?php
   
namespace App\Imports;
   
use App\Sales;
use App\Lead;
use App\Project;
use App\User;
use App\ModelUnit;
use App\UserRankPoint;
use App\Events\UserRankPointsEvent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
    
class SalesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //Leads Data
        $lead_inquiry_date = '';
        if (!empty($row['lead_date_inquired'])) {
            $lead_inquiry_date = date('Y-m-d 00:00:00', strtotime($row['lead_date_inquired']));
        }

        $lead_bday = '';
        if (!empty($row['lead_bday'])) {
            $lead_bday = date('Y-m-d', strtotime($row['lead_bday']));
        }

        $lead_project_interested = '';
        if (!empty($row['lead_project_interested'])) {
            $lead_project_interested = $this->getProjectNamebyShortcode($row['lead_project_interested']);
        }

        $lead_mobile = '';
        if (!empty($row['lead_mobile'])) {
            $mobile = str_replace(' ', '', $row['lead_mobile']);
            $check_zero_on_mobile = substr($mobile, 0, 1);
            if ($check_zero_on_mobile == 0) {
                $concat_mobile_first = substr($mobile, 0, 4);
                $concat_mobile_second = substr($mobile, 4, 3);
                $concat_mobile_third = substr($mobile, 7, 3);
            } else {
                $concat_mobile_first = '0'.substr($mobile, 0, 3);
                $concat_mobile_second = substr($mobile, 3, 3);
                $concat_mobile_third = substr($mobile, 6, 3);
            }

            $lead_mobile = '('.$concat_mobile_first.') '.$concat_mobile_second.'-'.$concat_mobile_third;
        }

        $lead_first_name = $row['lead_first_name'];
        $lead_middle_name = $row['lead_middle_name'];
        $lead_last_name = $row['lead_last_name'];
        $lead_address = $row['lead_address'];
        $lead_email = $row['lead_email'];
        $lead_civil_status = $row['lead_civil_status'];
        $lead_point_of_contact = $row['lead_point_of_contact'];
        //End of Leads Data

        //Sales Data
        $user_id = '';
        if (!empty($row['username'])) {
            $user_id = $this->getUserIdByUserName($row['username']);
        }

        $sales_reservation_date = '';
        if (!empty($row['sales_reservation_date'])) {
            $sales_reservation_date = date('Y-m-d 00:00:00', strtotime($row['sales_reservation_date']));
        }

        $sales_phase = $row['phase'];
        $sales_block = $row['block'];
        $sales_lot = $row['lot'];
        $sales_project = $row['project_shortcode'];
        $sales_model = $row['model_shortcode'];
        $total_contract_price = $row['total_contract_price'];
        $discount = $row['discount'];
        $processing_fee = $row['processing_fee'];
        $reservation_fee = $row['reservation_fee'];
        $equity = $row['equity'];
        $loanable_amount = $row['loanable_amount'];

        $financing = '';
        if (!empty($row['financing'])) { 
            $finance_data = $row['financing'];
            if (strtolower($finance_data) == 'inhouse') {
                $financing = 'INHOUSE';
            } else if (strtolower($finance_data) == 'hdmf') {
                $financing = 'HDMF';
            } else if (strtolower($finance_data) == 'cash') {
                $financing = 'Cash';
            } else if (strtolower($finance_data) == 'bank') {
                $financing = 'Bank';
            }
        }

        $terms = $row['terms'];
        $status = $row['status'];
        //End of Sales Data
        
        $data_leads = [
            'user_id' => $user_id,
            'date_inquired' => $lead_inquiry_date,
            'firstname' => $lead_first_name,
            'middlename' => $lead_middle_name,
            'lastname' => $lead_last_name,
            'address' => $lead_address,
            'mobileNo' => $lead_mobile,
            'email' => $lead_email,
            'status' => $lead_civil_status,
            'project' => $lead_project_interested,
            'birthday' => $lead_bday,
            'lead_status' => 'reserved',
            'important' => '0',
            'point_of_contact' => $lead_point_of_contact,
        ];

        $leads = Lead::create($data_leads);

        if ($leads) {
            $model_unit_id = '';
            $lot_area = '';
            $floor_area = '';
            if (!empty($sales_model)) {
                $model_unit = $this->getModelUnitIdByShortcode($sales_model);
                $model_unit_id = $model_unit['id'];
                $lot_area = $model_unit['lot_area'];
                $floor_area = $model_unit['floor_area'];
            }
            
            $data_sales = [
                'reservation_date' => $sales_reservation_date,
                'user_id' => $user_id,
                'lead_id' => $leads->id,
                'project_id' => $this->getProjectIdByShortcode($sales_project),
                'model_unit_id' => $model_unit_id,
                'lot_area' => $lot_area,
                'floor_area' => $floor_area,
                'phase' => $sales_phase,
                'block' => $sales_block,
                'lot' => $sales_lot,
                'total_contract_price' => $total_contract_price,
                'discount' => $discount,
                'processing_fee' => $processing_fee,
                'reservation_fee' => $reservation_fee,
                'equity' => $equity,
                'loanable_amount' => $loanable_amount,
                'financing' => $financing,
                'terms' => $terms,
                'commission_rate' => $this->setCommissionRate($this->getProjectIdByShortcode($sales_project),$user_id),
                'status' => $status
            ];

            $sales = Sales::create($data_sales);
            if ($sales){
                $id = $user_id;
                $plusPoint = ($total_contract_price - $discount)/100000;
                $get_points = $this->getUserRankPoints($user_id);
                $points = $get_points['sales_points'] + $plusPoint;
                $extra_points = $get_points['extra_points'];
                event(new UserRankPointsEvent($id, $points, $extra_points));
            }
            
        }
        // return new Sales([
        //     'name'     => $row['name'],
        //     'email'    => $row['email'], 
        //     'password' => \Hash::make($row['password']),
        // ]);
    }

    public function getUserRankPoints($userid)
    {
        $rank = UserRankPoint::where('user_id', $userid)->first();

        $result = [];
        if (!empty($rank)) {
            $result = [
                'sales_points' => $rank->sales_points,
                'extra_points' => $rank->extra_points
            ];
        }
        return $result;
    }

    public function getProjectNamebyShortcode($shortcode)
    {
        $imploded_shortcode = explode(',',$shortcode);
        $project = Project::whereIn('shortcode', $imploded_shortcode)->get();

        $project_name = [];
        if (!empty($project)) {
            foreach ($project as $projects) {
                $project_name [] = $projects->name;
            }
        }

        $imploded_result = implode(',', $project_name);
        return $imploded_result;
    }

    public function getUserIdByUserName($username)
    {
        $user = User::where('username', $username)->first();

        return $user->id;
    }

    public function getProjectIdByShortcode($shortcode)
    {
        $project = Project::where('shortcode', $shortcode)->first();

        $project_id = '';
        if (!empty($project)) {
            $project_id = $project->id;
        }
        return $project_id;
    }

    public function getModelUnitIdByShortcode($shortcode)
    {
        $model = ModelUnit::where('shortcode', $shortcode)->first();

        $model_data = [];
        if (!empty($model)) {
            $model_data = [
                'id' => $model->id,
                'lot_area' => $model->lot_area,
                'floor_area' => $model->floor_area
            ];
        }
        return $model_data;
    }

    public function setCommissionRate($project_id,$user_id)
    {
        ///$user = auth()->user()->id;/*set the id of the current user*/
        $user = $user_id;/*set the id of the current user*/
        $upLines = array(); /*instantiate the up line ids */
        $ctr = 1; /*array counter*/

        #this will loop until it gets all the user's up line IDs

        $upLines[$user] = 0;/*initialize the up line value to 0*/
        while($this->getUpLineIds($user) != null)
        {
            $user = $this->getUpLineIds($user);/*set the new user id*/
            $upLines[$user] = $ctr;/*set the user key value use for arranging the user by position or rank*/
            $ctr++;
        }


        $project_rate = Project::find($project_id); /*get the project rate*/
        $rate = $project_rate->commission_rate; /*instantiate the project rate*/

        arsort($upLines);/*this will arrange the Ids in descending order*/
        foreach ($upLines as $key => $value)
        {
            $user = User::find($key);

            if(!$user->hasRole(['super admin','account manager','online warrior']))
            {
                /*this will check if the project id is available on users commissions table project id column*/
                if($user->commissions()->where('project_id','=',$project_id)->count() > 0)
                {
                    /*if the commission was set to a specific project and it matches the sales project id*/
                    $user_rate = $user->commissions()->where('project_id','=',$project_id)->first()->commission_rate;
                }else{
                    $user_rate =  $user->commissions()->where('project_id','=',null)->first()->commission_rate;/*get the user commission rate*/
                }

                /*this conditional statement will be used if the commission rate offers by the project is lower
                or equal to the user's commission rate*/
                if($user_rate >= $rate)
                {
                    --$rate;
                }
                elseif($user_rate <= 0){

                    $rate = 0; /* commission rate is zero and will return an error message to the user*/
                }
                else{
                    $rate = $user_rate;
                }
            }
        }
        return $rate;

    }

    public function getUpLineIds($user_id)
    {
        $user = User::find($user_id);
        return $user->upline_id;
    }
}