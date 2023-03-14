<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\NotFoundException;
use App\Exception\StorageException;
use PDO;
use Throwable;

class NoteModel extends AbstractModel implements ModelInterface
{

    /**
     * @param int $pageNumber
     * @param int $pageSize
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     * @throws StorageException
     */
    public function list(
        int $pageNumber,
        int $pageSize,
        string $sortBy,
        string $sortOrder
    ): array {
        return $this->findBy(null, $pageNumber, $pageSize, $sortBy, $sortOrder);
    }

    /**
     * @param string $phrase
     * @param int $pageNumber
     * @param int $pageSize
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     * @throws StorageException
     */
    public function search(
        string $phrase,
        int $pageNumber,
        int $pageSize,
        string $sortBy,
        string $sortOrder
    ): array {
        return $this->findBy($phrase, $pageNumber, $pageSize, $sortBy, $sortOrder);
    }

    /**
     * @param array $data
     * @return void
     * @throws StorageException
     */
    public function create(array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $created_at = $this->conn->quote(date('Y-m-d H:i:s'));

            $query = "
                INSERT INTO notes(title, description, created_at) 
                VALUES($title, $description, $created_at)
                ";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się utworzyć notatki", 400, $e);
        }
    }

    /**
     * @param string $phrase
     * @return int
     * @throws StorageException
     */
    public function searchCount(string $phrase): int
    {
        try {
            $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
            $query = "SELECT COUNT(*) AS cn FROM notes WHERE title LIKE($phrase)";
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            $result = $result->fetch();
            if ($result === false) {
                throw new StorageException("Błąd przy próbie pobrania ilości notatek", 400);
            }
            return (int)$result['cn'];
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać informacji o liczbie notatek", 400, $e);
        }
    }

    /**
     * @return int
     * @throws StorageException
     */
    public function count(): int
    {
        try {
            $query = "SELECT COUNT(*) AS cn FROM notes";
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            $result = $result->fetch();
            if ($result === false) {
                throw new StorageException("Błąd przy próbie pobrania ilości notatek", 400);
            }
            return (int)$result['cn'];
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać informacji o liczbie notatek", 400, $e);
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws NotFoundException
     * @throws StorageException
     */
    public function get(int $id): array
    {
        try {
            $query = "SELECT * FROM notes WHERE id = $id";
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            $note = $result->fetch();
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać notatki", 400, $e);
        }
        if (!$note) {
            throw new NotFoundException("Notatka o id: $id nie istnieje.");
        }
        return $note;
    }

    /**
     * @param int $id
     * @param array $data
     * @return void
     * @throws StorageException
     */
    public function update(int $id, array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);

            $query = "
                UPDATE notes 
                SET title = $title, description = $description 
                WHERE id = $id
                ";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się zaktualizować notatki", 400, $e);
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws StorageException
     */
    public function delete(int $id): void
    {
        try {
            $query = "DELETE FROM notes WHERE id = $id";

            $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się usunąć notatki", 400, $e);
        }
    }

    /**
     * @param string|null $phrase
     * @param int $pageNumber
     * @param int $pageSize
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     * @throws StorageException
     */
    private function findBy(
        ?string $phrase,
        int $pageNumber,
        int $pageSize,
        string $sortBy,
        string $sortOrder
    ): array {
        try {
            $limit = $pageSize;
            $offset = ($pageNumber - 1) * $pageSize;
            if (!in_array($sortBy, ['created_at', 'title'])) {
                $sortBy = 'title';
            }

            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortOrder = 'desc';
            }

            $wherePart = '';
            if ($phrase) {
                $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
                $wherePart = ' WHERE title LIKE (' . $phrase . ') ';
            }

            $query = "SELECT id, title, created_at
                      FROM notes
                      $wherePart
                      ORDER BY $sortBy $sortOrder 
                      LIMIT $offset, $limit";
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            return $result->fetchAll();
        } catch (Throwable $e) {
            throw new StorageException("Nie udało się pobrać notatek", 400, $e);
        }
    }

}