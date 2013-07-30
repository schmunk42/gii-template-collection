<?php

class PartialViewProvider
{
    /**
     * Returns the viewFile for the column if exists otherwise it returns null
     * @return string
     * @todo detection
     */
    static public function resolveColumnViewFile($column, $codeModel)
    {
        if (!isset($codeModel->files[0])) {
            return null;
        }
        $viewDir   = self::getOutputViewDirectory($codeModel);
        $viewAlias = 'columns' . DIRECTORY_SEPARATOR . $column->name;
        $viewFile  = $viewDir . DIRECTORY_SEPARATOR . $viewAlias . '.php';
        return (file_exists($viewFile)) ? $viewAlias : null;
    }

    /**
     * Returns the viewFile for the relation if exists otherwise it returns null
     * @return string
     * @todo detection
     */
    static public function resolveRelationViewFile($relation, $codeModel)
    {
        if (!isset($codeModel->files[0])) {
            return null;
        }

        $viewDir   = $viewDir = self::getOutputViewDirectory($codeModel);
        $viewAlias = 'relations' . DIRECTORY_SEPARATOR . $relation[1];
        $viewFile  = $viewDir . DIRECTORY_SEPARATOR . $viewAlias . '.php';
        return (file_exists($viewFile)) ? $viewAlias : null;
    }


    static private function getOutputViewDirectory($codeModel)
    {
        $controllerDir  = dirname($codeModel->files[0]->path);
        $controllerName = strtolower(basename(str_replace('Controller', '', $codeModel->files[0]->path), ".php"));
        $viewDir        = str_replace('controllers', 'views/' . $controllerName, $controllerDir);
        return $viewDir;
    }
}