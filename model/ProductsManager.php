<?php
require_once 'DatabaseManager.php';
require_once __DIR__.'/../controller/config.php';

class ProductsManager extends DB
{
    public array $messages = [];
    public int $currentTarget = 0;

    function getShoes(int $start = 0, int $limit = defaultDbFetchLimit)
    {

    }

    function getClothing(int $start = 0, int $limit = defaultDbFetchLimit)
    {

    }

    function getAll(int $start = 0, int $limit = defaultDbFetchLimit)
    {
        return $this->runQuery("SELECT * FROM products where 1 LIMIT ?,?", 'ii', [$start, $limit]);
    }

    function GetHandbags(int $start = 0, int $limit = defaultDbFetchLimit)
    {

    }

}