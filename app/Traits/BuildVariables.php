<?php


namespace App\Traits;


trait BuildVariables
{

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
