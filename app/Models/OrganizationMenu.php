<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class OrganizationMenu extends Model
{
    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at'
    ];

    public static function getMenus()
    {
        try {
            $menu_table = 'organization_menus';
            $query = <<<SQL
            WITH RECURSIVE MenuTree AS (
                -- Base case (root level menus)
                SELECT id, name, parent_id, routes, is_heading, sort_by, 0 AS level
                FROM $menu_table
                WHERE parent_id = 0 AND status = 1

                UNION ALL

                -- Recursive case (children of previous level)
                SELECT m.id, m.name, m.parent_id, m.routes, m.is_heading, m.sort_by, mt.level + 1
                FROM $menu_table m
                INNER JOIN MenuTree mt ON m.parent_id = mt.id
                WHERE m.status = 1
            )
            SELECT mt.*, 
                CASE 
                    WHEN mt.level = 1 THEN (SELECT name FROM $menu_table WHERE id = mt.parent_id)
                    WHEN mt.level = 2 THEN (SELECT name FROM $menu_table WHERE id = (SELECT parent_id FROM $menu_table WHERE id = mt.parent_id))
                    ELSE mt.name
                END AS first_parent,
                CASE 
                    WHEN mt.level = 2 THEN (SELECT name FROM $menu_table WHERE id = mt.parent_id)
                    ELSE mt.name
                END AS second_parent
            FROM MenuTree mt
            ORDER BY mt.sort_by ASC, mt.id ASC;
            SQL;

            return DB::select($query);
        } catch (\Exception $e) {
            \Log::error('Error fetching menus: ' . $e->getMessage());
            return [];
        }
    }
}
