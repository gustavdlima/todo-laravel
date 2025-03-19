<?php

namespace App\Http\Controllers;

use App\Domain\Task\Services\TaskService;
use App\Domain\Task\ValueObjects\TaskStatus;
use App\Http\Resources\TaskDTO;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
	private $taskService;

	public function __construct(TaskService $taskService)
	{
		$this->taskService = $taskService;
	}

	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'title' => 'required|string|max:255',
			'description' => 'nullable|string',
			'due_date' => 'required|date',
			'user_id' => 'required|integer'
		]);

		try {
			$dueDate = Carbon::parse($validatedData['due_date']);
		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Invalid due date format.',
				'message' => $e->getMessage(),
			], 400);
		}

		try {
			$task = $this->taskService->createTask(
				$validatedData['title'],
				$validatedData['description'],
				$dueDate,
				$validatedData['user_id']
			);

			$taskDTO = TaskDTO::fromEntity($task);

			return response()->json($taskDTO, 201);
		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Error creating task.',
				'message' => $e->getMessage(),
			], 500);
		}
	}

	public function update(Request $request, int $taskId)
	{
		$validatedData = $request->validate([
			'title' => 'sometimes|string|max:255',
			'description' => 'nullable|string',
			'status' => 'sometimes|in:pending,in_progress,completed',
			'due_date' => 'sometimes|date',
		]);

		try {
			$status = isset($validatedData['status']) ? TaskStatus::fromString($validatedData['status']) : null;

			$dueDate = null;
			if (isset($validatedData['due_date'])) {
				$dueDate = Carbon::parse($validatedData['due_date']);
			}
		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Invalid due date format.',
				'message' => $e->getMessage(),
			], 400);
		}

		try {
			$task = $this->taskService->updateTask(
				$taskId,
				$validatedData['title'] ?? null,
				$validatedData['description'] ?? null,
				$status,
				$dueDate
			);

			return response()->json($task);
		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Error updating the task.',
				'message' => $e->getMessage(),
			], 500);
		}
	}

	public function filterByStatus(Request $request, int $userId)
	{
		$validatedData = $request->validate([
			'status' => 'required|in:pending,in_progress,completed',
		]);

		$status = TaskStatus::fromString($validatedData['status']);
		$tasks = $this->taskService->getTasksByStatus($userId, $status);

		return response()->json($tasks);
	}

	public function filterByCreationDate(Request $request, int $userId)
	{
		$validatedData = $request->validate([
			'creation_date' => 'required|date'
		]);

		try {
			$date = Carbon::parse($validatedData['creation_date']);
		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Invalid creation date format.',
				'message' => $e->getMessage(),
			], 400);
		}

		try {
			$tasks = $this->taskService->getTaskByCreationDate($userId, $date);
			return response()->json($tasks);
		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Error retrieving tasks.',
				'message' => $e->getMessage(),
			], 500);
		}
	}

	public function addComment(Request $request, int $taskId)
	{
		$validatedData = $request->validate([
			'content' => 'required|string',
			'user_id' => 'required|integer',
		]);

		$this->taskService->addComment(
			$taskId,
			$validatedData['content'],
			$validatedData['user_id']
		);

		return response()->json(['message' => 'Comment added successfully'], 201);
	}

	public function delete(int $taskId)
	{
		$this->taskService->deleteTask($taskId);

		return response()->json(['message' => 'Task deleted successfully']);
	}
}
