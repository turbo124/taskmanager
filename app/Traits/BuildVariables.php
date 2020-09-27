<?php


namespace App\Traits;


trait BuildVariables
{
    public function formatNotes($entity) {
        if(isset($entity->public_notes) && strlen($entity->public_notes) > 0) {
            $entity->public_notes = $this->parseVariables($entity->public_notes);
        }

        if(isset($entity->private_notes) && strlen($entity->private_notes) > 0) {
            $entity->private_notes = $this->parseVariables($entity->private_notes);
        }

        return $entity;
    }

    /**
     * @param $amount
     */
    public function parseVariables($content)
    {
        $this->objPdf->build();

        $labels = $this->objPdf->getLabels();
        $values = $this->objPdf->getValues();

        $content = $this->objPdf->parseLabels($labels, $content);
        $content = $this->objPdf->parseValues($values, $content);

        return $content;
    }
}
