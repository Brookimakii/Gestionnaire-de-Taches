<?php

	namespace App\Entity;

	use App\Repository\TaskListRepository;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\ORM\Mapping as ORM;

	#[ORM\Entity(repositoryClass: TaskListRepository::class)]
	class TaskList {
		#[ORM\Id]
		#[ORM\GeneratedValue]
		#[ORM\Column]
		private ?int $id = null;

		#[ORM\Column(length: 255)]
		private ?string $title = null;

		#[ORM\Column(length: 1000, nullable: true)]
		private ?string $description = null;

		#[ORM\ManyToOne(inversedBy: 'taskLists')]
		#[ORM\JoinColumn(nullable: false)]
		private ?User $owner = null;

		/**
		 * @var Collection<int, Task>
		 */
		#[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'taskList', orphanRemoval: true)]
		private Collection $tasks;

		/**
		 * @var Collection<int, User>
		 */
		#[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'collaborateOn')]
		private Collection $sharedWith;

		public function __construct() {
			$this->tasks = new ArrayCollection();
			$this->sharedWith = new ArrayCollection();
		}

		public function getId(): ?int {
			return $this->id;
		}

		public function getTitle(): ?string {
			return $this->title;
		}

		public function setTitle(string $title): static {
			$this->title = $title;

			return $this;
		}

		public function getDescription(): ?string {
			return $this->description;
		}

		public function setDescription(?string $description): static {
			$this->description = $description;

			return $this;
		}

		public function getOwner(): ?User {
			return $this->owner;
		}

		public function setOwner(?User $owner): static {
			$this->owner = $owner;

			return $this;
		}

		/**
		 * @return Collection<int, Task>
		 */
		public function getTasks(): Collection {
			return $this->tasks;
		}

		public function addTask(Task $task): static {
			if (!$this->tasks->contains($task)) {
				$this->tasks->add($task);
				$task->setTaskList($this);
			}

			return $this;
		}

		public function removeTask(Task $task): static {
			if ($this->tasks->removeElement($task)) {
				// set the owning side to null (unless already changed)
				if ($task->getTaskList() === $this) {
					$task->setTaskList(null);
				}
			}

			return $this;
		}

		/**
		 * @return Collection<int, User>
		 */
		public function getSharedWith(): Collection {
			return $this->sharedWith;
		}

		public function addSharedWith(User $sharedWith): static {
			if (!$this->sharedWith->contains($sharedWith)) {
				$this->sharedWith->add($sharedWith);
			}

			return $this;
		}

		public function removeSharedWith(User $sharedWith): static {
			$this->sharedWith->removeElement($sharedWith);

			return $this;
		}
	}
