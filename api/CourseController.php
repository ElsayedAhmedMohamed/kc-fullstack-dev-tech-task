<?php

declare(strict_types=1);

class CourseController
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): void
    {
        $result = $this->db->query("SELECT courses.*, categories.name as main_category_name FROM courses LEFT JOIN categories ON courses.category_id = categories.id");
        $courses = [];

        while ($row = $result->fetch_assoc()) {

			$courses[] = [
			'id' => $row['id'],
			'name' => $row['name'],
			'description' => $row['description'],
			'preview' => $row['image_preview'],
			'category_id' => $row['category_id'],
			'main_category_name' => $row['main_category_name'],
			'created_at' => $row['created_at'],
			'updated_at' => $row['updated_at']
			];
	
	
        }

        if ($courses) {
            echo json_encode($courses);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'courses not found']);
        }
		
		
    }

    public function getById($id): void
    {
        $stmt = $this->db->prepare("SELECT * FROM courses LEFT JOIN categories ON courses.category_id = categories.id WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $result = $stmt->get_result();
         while ($row = $result->fetch_assoc()) {

			$courses[] = [
			'id' => $row['id'],
			'name' => $row['name'],
			'description' => $row['description'],
			'preview' => $row['image_preview'],
			'category_id' => $row['category_id'],
			'main_category_name' => $row['main_category_name'],
			'created_at' => $row['created_at'],
			'updated_at' => $row['updated_at']
			];
	
	
        }
        if ($course) {
            echo json_encode($course);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
        }
    }

    public function create(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $image_preview = trim($data['image_preview'] ?? '');
        $category_id = trim($data['category_id'] ?? '');


        if ($name === '' && $description === '' && $image_preview === '' && $category_id === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Name & Description & Image & Category  is required']);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO courses (name, description , image_preview , category_id) VALUES (?, ?, ?, ?)");
        if ($parentId === 0) {
            $parentId = null;
        }
        $stmt->bind_param("ssss", $name, $description,$image_preview , $category_id);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['id' => $stmt->insert_id, 'name' => $name]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create Course']);
        }
    }

    public function update(int $id): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $image_preview = trim($data['image_preview'] ?? '');
        $category_id = trim($data['category_id'] ?? '');

        if ($name === '' && $description === '' && $image_preview === '' && $category_id === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Name & Description & Image & Category  is required']);
            return;
        }

        $stmt = $this->db->prepare("UPDATE courses SET name = ?, description = ?, image_preview = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("sssss", $name, $description,$image_preview , $category_id, $id);

        if ($stmt->execute()) {
            echo json_encode(['id' => $id, 'name' => $name]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update Course']);
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Course deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete Course']);
        }
    }
}