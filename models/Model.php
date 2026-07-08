<?php
class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $timestamps = true;

    public function __construct($table = null) {
        $this->db = Database::getInstance();
        if ($table) $this->table = $table;
    }

    public function setTable($name) {
        $this->table = $name;
        return $this;
    }

    public function all($orderBy = 'id', $direction = 'DESC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findBy($column, $value, $limit = null) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? " . ($limit ? "LIMIT {$limit}" : ""));
        $stmt->bind_param("s", $value);
        $stmt->execute();
        if ($limit === 1) {
            return $stmt->get_result()->fetch_assoc();
        }
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        if ($this->timestamps) {
            $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
            $data['updated_at'] = $data['updated_at'] ?? date('Y-m-d H:i:s');
        }
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $types = str_repeat('s', count($data));
        $values = array_values($data);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            return $this->db->insertId();
        }
        return false;
    }

    public function update($id, $data) {
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        $sets = implode(' = ?, ', array_keys($data)) . ' = ?';
        $types = str_repeat('s', count($data));
        $values = array_values($data);

        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$sets} WHERE {$this->primaryKey} = ?");
        $types .= 'i';
        $values[] = $id;
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function count($where = '1=1') {
        $result = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE {$where}");
        return $result->fetch_assoc()['total'];
    }

    public function paginate($page = 1, $perPage = 20, $where = '1=1', $orderBy = 'id', $direction = 'DESC') {
        $offset = ($page - 1) * $perPage;
        $total = $this->count($where);
        $totalPages = ceil($total / $perPage);
        $sql = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderBy} {$direction} LIMIT {$offset}, {$perPage}";
        $data = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages
        ];
    }

    public function where($column, $operator = '=', $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} {$operator} ?");
        $stmt->bind_param("s", $value);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function query($sql, $params = []) {
        if (empty($params)) {
            return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        }
        $stmt = $this->db->prepare($sql);
        $types = '';
        foreach ($params as $p) {
            $types .= is_int($p) ? 'i' : (is_float($p) ? 'd' : 's');
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function queryRow($sql, $params = []) {
        $results = $this->query($sql, $params);
        return $results[0] ?? null;
    }
}
