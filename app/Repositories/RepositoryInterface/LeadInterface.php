<?php


namespace App\Repositories\RepositoryInterface;


interface LeadInterface
{
    /**
     * @param $lead_id
     * @return mixed
     */
    public function viewReservedUnits($lead_id);

    /**
     * @param $lead_id
     * @return mixed
     */
    public function viewById($lead_id);

    /**
     * assign leads to warrior
     * @param $lead_id
     * @param $warrior_id
     * @return mixed
     */
    public function assignLeadsToWarrior($lead_id, $warrior_id);

    /**
     * @param $leads
     * @param array $rawColumns
     * @return mixed
     */
    public function leadsTable($leads, array $rawColumns);

}
