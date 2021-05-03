<?php

function getArrayFromFile(string $path)
{
    return json_decode(file_get_contents($path));
}

function flatten(array $items, ?array &$r) {
    foreach($items as $item) {
        $c = isset($item->children) ? $item->children : null;
        unset($item->children);
        $r[]= $item;
        if($c) {
            flatten($c, $r);
        }
    }

    return $r;
}

function findCategoryElement(int $id, array $list)
{
    foreach($list as $item) {
        if(intval($item->category_id) === $id) {
            return $item;
        }
    }
}

function execute(array $flattenTree, array $list) {
    foreach ($flattenTree as $item) {
        $categoryEl = findCategoryElement($item->id, $list);
        if ($categoryEl) {
            $item->name = $categoryEl->translations->pl_PL->name;
        } else {
            $item->name = [];
        }
        $item->children = [];
    }

    print_r(json_encode($flattenTree));
}

execute(flatten(getArrayFromFile('./tree.json'), $r), getArrayFromFile('./list.json'));