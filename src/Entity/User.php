<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    /**
     * @var Collection<int, TaskList>
     */
    #[ORM\OneToMany(targetEntity: TaskList::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $taskLists;

    /**
     * @var Collection<int, TaskList>
     */
    #[ORM\ManyToMany(targetEntity: TaskList::class, mappedBy: 'sharedWith')]
    private Collection $collaborateOn;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'assignees')]
    private Collection $tasks;

    public function __construct()
    {
        $this->taskLists = new ArrayCollection();
        $this->collaborateOn = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, TaskList>
     */
    public function getTaskLists(): Collection
    {
        return $this->taskLists;
    }

    public function addTaskList(TaskList $taskList): static
    {
        if (!$this->taskLists->contains($taskList)) {
            $this->taskLists->add($taskList);
            $taskList->setOwner($this);
        }

        return $this;
    }

    public function removeTaskList(TaskList $taskList): static
    {
        if ($this->taskLists->removeElement($taskList)) {
            // set the owning side to null (unless already changed)
            if ($taskList->getOwner() === $this) {
                $taskList->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TaskList>
     */
    public function getCollaborateOn(): Collection
    {
        return $this->collaborateOn;
    }

    public function addCollaborateOn(TaskList $collaborateOn): static
    {
        if (!$this->collaborateOn->contains($collaborateOn)) {
            $this->collaborateOn->add($collaborateOn);
            $collaborateOn->addSharedWith($this);
        }

        return $this;
    }

    public function removeCollaborateOn(TaskList $collaborateOn): static
    {
        if ($this->collaborateOn->removeElement($collaborateOn)) {
            $collaborateOn->removeSharedWith($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->addAssignee($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            $task->removeAssignee($this);
        }

        return $this;
    }
}
