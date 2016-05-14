<?php

namespace Kordy\Auzo\Services;

use Kordy\Auzo\Facades\AuzoAbilityFacade;
use Kordy\AuzoTools\Services\GenerateAbilities;

class GenerateAbilitiesToDB extends GenerateAbilities
{
    /**
     * Save generated models and fields abilities to database
     *
     * @param string $delimiter
     * @return GenerateAbilities
     * @throws \Exception
     */
    public function saveToDB($delimiter = '.')
    {
        $this->saveModelToDB($delimiter);
        
        $this->saveFieldsToDB();
        
        return $this;
    }

    /**
     * Save generated model abilities to database
     *
     * @param string $delimiter
     * @return bool
     * @throws \Exception
     */
    public function saveModelToDB($delimiter = '.')
    {
        if (empty($this->model_crud_abilities)) {
            throw new \Exception('No model abilities found!');
        }

        $model_abilities = array_flatten($this->model_crud_abilities);
        $saved_abilities = [];
        foreach ($model_abilities as $model_ability) {
            list($model_name, $crud) = explode($delimiter, $model_ability);
            $saved_abilities[] = AuzoAbilityFacade::create([
                'name' => $model_ability,
                // set model name as a tag
                'tag' => $model_name
            ]);
        }
        return $saved_abilities;
    }

    /**
     * Save generated fields abilities to database
     * 
     * @return bool
     * @throws \Exception
     */
    public function saveFieldsToDB()
    {
        if (empty($this->fields_crud_abilities)) {
            throw new \Exception('No fields abilities found!');
        }

        $saved_abilities = [];
        foreach ($this->fields_crud_abilities as $field_abilities) {
            foreach ($field_abilities as $crud => $field_ability) {
                $parent_model_ability = $this->model_crud_abilities[$crud];
                $saved_abilities[] = AuzoAbilityFacade::create([
                    'name' => $field_ability,
                    // set parent model ability name as a tag
                    'tag' => $parent_model_ability
                ]);
            }
        }
        return $saved_abilities;
    }
}