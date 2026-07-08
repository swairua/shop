<?php
class Category extends Model {
    protected $table = 'categories';
    protected $fillable = ['parent_id', 'name', 'slug', 'description', 'image', 'icon', 'meta_title', 'meta_description', 'sort_order', 'status'];

    public function getTree($parentId = null, $depth = 0) {
        $results = [];
        $parentWhere = $parentId === null ? "AND parent_id IS NULL" : "AND parent_id = " . intval($parentId);
        $sql = "SELECT * FROM categories WHERE status = 'active' {$parentWhere} ORDER BY sort_order ASC, name ASC";
        $categories = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);

        foreach ($categories as $category) {
            $category['depth'] = $depth;
            $category['children'] = $this->getTree($category['id'], $depth + 1);
            $results[] = $category;
        }
        return $results;
    }

    public function getFlatList($parentId = null, $depth = 0, $excludeId = null) {
        $results = [];
        $parentWhere = $parentId === null ? "AND parent_id IS NULL" : "AND parent_id = " . intval($parentId);
        $excludeWhere = $excludeId ? "AND id != " . intval($excludeId) : "";
        $sql = "SELECT * FROM categories WHERE status = 'active' {$parentWhere} {$excludeWhere} ORDER BY sort_order ASC, name ASC";
        $categories = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);

        foreach ($categories as $category) {
            $category['depth'] = $depth;
            $results[] = $category;
            $children = $this->getFlatList($category['id'], $depth + 1, $excludeId);
            $results = array_merge($results, $children);
        }
        return $results;
    }

    public function getBreadcrumbs($categoryId) {
        $breadcrumbs = [];
        while ($categoryId) {
            $cat = $this->find($categoryId);
            if (!$cat) break;
            $breadcrumbs[] = $cat;
            $categoryId = $cat['parent_id'];
        }
        return array_reverse($breadcrumbs);
    }

    public function getProductCount($categoryId) {
        $cats = $this->getCategoryIdsRecursive($categoryId);
        if (empty($cats)) return 0;
        $ids = implode(',', $cats);
        $result = $this->db->query("SELECT COUNT(*) as total FROM products WHERE category_id IN ({$ids}) AND status = 'active'");
        return $result->fetch_assoc()['total'];
    }

    public function getCategoryIdsRecursive($categoryId) {
        $ids = [$categoryId];
        $children = $this->db->query("SELECT id FROM categories WHERE parent_id = " . intval($categoryId))->fetch_all(MYSQLI_ASSOC);
        foreach ($children as $child) {
            $ids = array_merge($ids, $this->getCategoryIdsRecursive($child['id']));
        }
        return $ids;
    }

    public function getDropdownOptions($selectedId = null, $parentId = null, $depth = 0, $excludeId = null) {
        $html = '';
        $parentWhere = $parentId === null ? "AND parent_id IS NULL" : "AND parent_id = " . intval($parentId);
        $excludeWhere = $excludeId ? "AND id != " . intval($excludeId) : "";
        $sql = "SELECT * FROM categories WHERE status = 'active' {$parentWhere} {$excludeWhere} ORDER BY sort_order ASC, name ASC";
        $categories = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        foreach ($categories as $cat) {
            $sel = $selectedId == $cat['id'] ? 'selected' : '';
            $prefix = $depth > 0 ? str_repeat('&mdash;', $depth) . ' ' : '';
            $html .= "<option value=\"{$cat['id']}\" {$sel}>{$prefix}{$cat['name']}</option>";
            $html .= $this->getDropdownOptions($selectedId, $cat['id'], $depth + 1, $excludeId);
        }
        return $html;
    }

    public function getParentOptions($selectedId = null, $excludeId = null) {
        $html = '<option value="">No Parent (Top Level)</option>';
        $html .= $this->getDropdownOptions($selectedId, null, 0, $excludeId);
        return $html;
    }
}
