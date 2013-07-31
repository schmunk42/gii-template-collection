<?php

class GtcPartialViewProvider extends GtcCodeProvider
{
    public function generateActiveField($modelClass, $column)
    {
        if ($view = $this->resolveColumnViewFile($column)) {
            return "\$this->renderPartial('{$view}', array('model'=>\$model, 'form' => \$form))";
        }
    }

    public function generateRelationField($modelClass, $column)
    {
        if ($view = $this->resolveRelationViewFile($column)) {
            return "\$this->renderPartial('{$view}', array('model'=>\$model, 'form' => \$form))";
        }
    }

    /**
     * Returns the viewFile for the column if exists otherwise it returns null
     * @return string
     */
    public function resolveColumnViewFile($column)
    {
        if (!isset($this->codeModel->files[0])) {
            return null;
        }
        $viewDir   = self::getOutputViewDirectory();
        $viewAlias = 'columns' . DIRECTORY_SEPARATOR . $column->name;
        $viewFile  = $viewDir . DIRECTORY_SEPARATOR . $viewAlias . '.php';
        return (file_exists($viewFile)) ? $viewAlias : null;
    }

    /**
     * Returns the viewFile for the relation if exists otherwise it returns null
     * @return string
     */
    public function resolveRelationViewFile($relation)
    {
        if (!isset($this->codeModel->files[0])) {
            return null;
        }

        $viewDir   = $viewDir = self::getOutputViewDirectory();
        $viewAlias = 'relations' . DIRECTORY_SEPARATOR . $relation[1];
        $viewFile  = $viewDir . DIRECTORY_SEPARATOR . $viewAlias . '.php';
        return (file_exists($viewFile)) ? $viewAlias : null;
    }


    private function getOutputViewDirectory()
    {
        $controllerDir  = dirname($this->codeModel->files[0]->path);
        $controllerName = strtolower(basename(str_replace('Controller', '', $this->codeModel->files[0]->path), ".php"));
        $viewDir        = str_replace('controllers', 'views/' . $controllerName, $controllerDir);
        return $viewDir;
    }
}