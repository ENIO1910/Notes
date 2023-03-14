<?php

declare(strict_types=1);

namespace App;

class View
{
    public function render(string $page, array $params = []): void
    {
        $params = $this->escape($params);

        require_once("templates/layout.php");
    }

    private function escape(array $params): array
    {
        $escapeParams = [];

        foreach ($params as $key => $param) {
            switch (true) {
                case is_array($param):
                    $escapeParams[$key] = $this->escape($param);
                    break;
                case is_int($param):
                    $escapeParams[$key] = $param;
                    break;
                case $param:
                    $escapeParams[$key] = htmlentities($param);
                    break;
                default:
                    $escapeParams[$key] = $param;
                    break;
            }
        }
        return $escapeParams;
    }
}