<?php


namespace App\Repositories;


use App\Repositories\RepositoryInterface\RequirementsInterface;
use App\Requirement;
use App\Template;

class RequirementsRepository implements RequirementsInterface
{
    public function getRequirementsTemplate($template_id)
    {
        return Template::where('id',$template_id);
    }

    public function requirementTemplate($template_id)
    {
        return $this->getRequirementsTemplate($template_id)->first();
    }

    public function requirementTemplates($template_id)
    {
        return $this->getRequirementsTemplate($template_id)->get();
    }

    public function duplicate(array $templates)
    {
        foreach ($templates as $template)
        {
            $oldTemplate = collect($this->requirementTemplate($template))->except(['id','created_at','updated_at','deleted_at'])->toArray();
            $newTemplate = $this->create(new Template(), $this->addCopySuffixToTemplateName($oldTemplate));
            $this->saveRequirements($template, $newTemplate->id);
        }
    }

    /**
     * add copy suffix to the requirement template name
     * @param $requirementTemplate
     * @return array
     */
    private function addCopySuffixToTemplateName($requirementTemplate): array
    {
        $collection = collect($requirementTemplate);

        $copy = $collection->map(function ($item, $key) {
            if($key === "name")
            {
                $item .= " - Copy";
            }
            return $item;
        });

        return $copy->all();
    }

    public function create(Template $template, array $data)
    {
        return $template::create($data);
    }

    public function saveRequirements($old_template_id, $new_template_id): RequirementsRepository
    {
        $oldTemplate = Requirement::where('template_id', $old_template_id)->get();

        foreach ($oldTemplate as $template)
        {
            $newTemplate = new Requirement();
            $newTemplate->template_id = $new_template_id;
            $newTemplate->description = $template->description;
            $newTemplate->save();
        }

        return $this;
    }
}
