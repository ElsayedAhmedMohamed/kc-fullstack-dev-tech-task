<?php

declare(strict_types=1);

class CategoryController
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }




    public function getAll(): void
    {
		
        $result = $this->db->query("SELECT categories.*, COUNT(courses.id) AS count_of_courses
        FROM categories
        LEFT JOIN courses ON courses.category_id = categories.id
        GROUP BY categories.id");
        $categories = [];

        while ($row = $result->fetch_assoc()) {
			
			$categories[] = [
			'id' => $row['id'],
			'parent_id' => $row['parent_id'],
			'name' => $row['name'],
			'description' => $row['description'],
			'count_of_courses' => $row['count_of_courses'],
			'subcategories' => $this->getSubcategories($row['id']),
			'created_at' => $row['created_at'],
			'updated_at' => $row['updated_at']
			];
	
	
        }
		
        if ($categories) {
            echo json_encode($categories);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'categories not found']);
        }
		
		
    }

    public function getById($id): void
    {
        $stmt = $this->db->prepare("SELECT categories.* ,COUNT(courses.id) AS count_of_courses  FROM categories LEFT JOIN courses ON courses.category_id = categories.id WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result();
		
        $category = [];

        while ($row = $result->fetch_assoc()) {

			$category[] = [
			'id' => $row['id'],
			'name' => $row['name'],
			'description' => $row['description'],
			'count_of_courses' => $row['count_of_courses'],
			'subcategories' => $this->getSubcategories($row['id']),
			'created_at' => $row['created_at'],
			'updated_at' => $row['updated_at']
			];
	
	
        }
		

        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
        }
    }

    public function create(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? '');
        $parentId = isset($data['parent_id']) ? (int) $data['parent_id'] : null;

        if ($name === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Name is required']);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
        if ($parentId === 0) {
            $parentId = null;
        }
        $stmt->bind_param("ss", $name, $parentId);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['id' => $stmt->insert_id, 'name' => $name]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create category']);
        }
    }

    public function update($id): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? '');
		$parentId = isset($data['parent_id']) ? (int) $data['parent_id'] : null;

        if ($name === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Name is required']);
            return;
        }

        $stmt = $this->db->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
        $stmt->bind_param("sss", $name, $parentId, $id);

        if ($stmt->execute()) {
            echo json_encode(['id' => $id, 'name' => $name]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update category']);
        }
    }

    public function delete($id): void
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Category deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete category']);
        }
    }
	
	
	
    public function getSubcategories($parentId = null): array
    {
		
        $stmt = $this->db->prepare("SELECT categories.* ,COUNT(courses.id) AS count_of_courses  FROM categories LEFT JOIN courses ON courses.category_id = categories.id
		WHERE categories.parent_id = ?");
		$stmt->bind_param("s", $parentId);
		$stmt->execute();
		$result = $stmt->get_result();
        $Subcategories = [];


        while ($row = $result->fetch_assoc()) {

			$Subcategories[] = [
			'id' => $row['id'],
			'name' => $row['name'],
			'description' => $row['description'],
			'count_of_courses' => $row['count_of_courses'],
			'created_at' => $row['created_at'],
			'updated_at' => $row['updated_at']
			];
	
	
        }
		
        return $Subcategories;
		
    }
	
}