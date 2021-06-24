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
            if($this->view($sales_id)->update(['drive_link' => $data['drive_link']]))
            {
                return response(['success' => true, 'message' => 'Form successfully updated!']);
            }
            return response(['success' => false, 'message' => 'An error occurred'],400);
        }
        return response(['success' => false, 'message' => 'An error occurred'],404);
    }

    /**
     * @param $sales_id
     * @return bool
     */
    private function salesExists($sales_id): bool
    {
        return $this->view($sales_id)->count() > 0;
    }

    public function getClientRequirementsCount($sales_id): string
    {
        $TotalRequirements = Requirement::where('template_id',$this->viewSpecifiedSale($sales_id)['template_id'])->count();
        return $this->getSubmittedRequirements($sales_id).'/'.$TotalRequirements;

    }

    public function getSubmittedRequirements($sales_id): int
    {
        $exists = 0;
        foreach ($this->viewSpecifiedSale($sales_id)->requirements as $requirement)
        {
            if($requirement['exists'] === true)
            {
                $exists++;
            }
        }
        return $exists;
    }
}
