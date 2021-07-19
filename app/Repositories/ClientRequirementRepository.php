<?php


namespace App\Repositories;


use App\ClientRequirement;
use App\Repositories\RepositoryInterface\ClientRequirementInterface;
use App\Requirement;

class ClientRequirementRepository implements ClientRequirementInterface
{
    public function view($sales_id)
    {
        return ClientRequirement::where('sales_id',$sales_id);
    }

    public function viewBySales($sales_id)
    {
        return $this->view($sales_id)->get();
    }

    public function viewSpecifiedSale($sales_id)
    {
        return $this->view($sales_id)->first();
    }

    public function save(ClientRequirement $clientRequirement, $requirements)
    {
        return $clientRequirement::create($requirements);
    }

    public function saveDrive(array $data)
    {
        $sales_id = $data['sales_id'];
        if($this->salesExists($sales_id))
        {
            //check if extra requirements added
            if(array_key_exists('extraRequirements', $data))
            {
                ///extra requirements added from the existing
                $collection = collect($this->viewSpecifiedSale($sales_id)->requirements);

                $updatedRequirements = $collection->merge($this->formatExtraRequirements($data['extraRequirements'], $this->viewSpecifiedSale($sales_id)->requirements));

                if($this->view($sales_id)->update(['drive_link' => $data['drive_link'], 'requirements' => $updatedRequirements->all()]))
                {
                    return response(['success' => true, 'message' => 'Form successfully updated!']);
                }
                return response(['success' => false, 'message' => 'An error occurred'],400);
            }
            else{
                //only google drive link was save because there was no extra requirements added
                if($this->view($sales_id)->update(['drive_link' => $data['drive_link']]))
                {
                    return response(['success' => true, 'message' => 'Form successfully updated!']);
                }
                return response(['success' => false, 'message' => 'An error occurred'],400);
            }
        }
        return response(['success' => false, 'message' => 'An error occurred'],404);
    }

    /**
     * get the max value in IDs
     * @param $requirements
     * @return mixed
     */
    private function getMaxValue($requirements)
    {
        return collect($requirements)->max('id');
    }

    /**
     * This will format the extra requirements using the existing template
     * @param array $extraRequirements
     * @param array $existingRequirements
     * @return array
     */
    private function formatExtraRequirements(array $extraRequirements, array $existingRequirements)
    {
        $additionalRequirements = array();
        $lastValue = $this->getMaxValue($existingRequirements)+1;
        foreach ($extraRequirements as $key => $value)
        {
            if($value !== null)
            {
                $additionalRequirements[$key] = array(
                    'id' => $lastValue++,
                    'template_id' => null,
                    'description' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'exists' => false,
                );
            }
        }

        return $additionalRequirements;
    }

    /**
     * @param $sales_id
     * @return bool
     */
    private function salesExists($sales_id): bool
    {
        return $this->view($sales_id)->count() > 0;
    }


    public function getSubmittedRequirements($sales_id)
    {
        if($this->view($sales_id)->count() > 0 )
        {
            $submitted = 0;
            $totalRequirements = 0;
            foreach ($this->viewSpecifiedSale($sales_id)->requirements as $requirement)
            {
                $totalRequirements++;
                if($requirement['exists'] === true)
                {
                    $submitted++;
                }
            }
            return $submitted.'/'.$totalRequirements;
        }
        return "none";
    }
}
