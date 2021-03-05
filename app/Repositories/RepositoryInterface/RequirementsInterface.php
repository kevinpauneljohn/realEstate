<?php


namespace App\Repositories\RepositoryInterface;


use App\Template;

interface RequirementsInterface
{
    /**
     * get requirements template
     * @param $template_id
     * @return mixed
     */
    public function getRequirementsTemplate($template_id);

    /**
     * get a specified requirement template
     * @param $template_id
     * @return mixed
     */
    public function requirementTemplate($template_id);

    /**
     * get all list of requirement templates
     * @param $template_id
     * @return mixed
     */
    public function requirementTemplates($template_id);

    /**
     * duplicate a specified template
     * @param array $templates
     * @return mixed
     */
    public function duplicate(array $templates);

    /**
     * @param Template $template
     * @param array $data
     * @return mixed
     */
    public function create(Template $template, array $data);

    /**
     * @param $old_template_id
     * @param $new_template_id
     * @return mixed
     */
    public function saveRequirements($old_template_id, $new_template_id);
}
