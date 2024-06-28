<?php

	namespace App\Repository;

	use App\Entity\Task;
	use App\Entity\TaskList;
	use App\Entity\User;
	use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
	use Doctrine\Persistence\ManagerRegistry;

	/**
	 * @extends ServiceEntityRepository<Task>
	 */
	class TaskRepository extends ServiceEntityRepository {
		public function __construct(ManagerRegistry $registry) {
			parent::__construct($registry, Task::class);
		}

//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
		/**
		 * @return Task[] Returns an array of Task objects
		 */
		public function getTaskFromList(TaskList $taskList) {
			return $this->createQueryBuilder('t')
				->andWhere('t.taskList = :taskList')
				->setParameter('taskList', $taskList)
				->orderBy('t.id', 'ASC')
				->setMaxResults(10)
				->getQuery()
				->getResult();
		}

		public function getTaskAssignTo($user, TaskList $taskList) {
			return $this->createQueryBuilder('t')
				->leftJoin('t.assignees', 'a')
				->andWhere('t.taskList = :taskList')
				->andWhere("a.id = :user OR a.id IS NULL")
				->setParameter('taskList', $taskList)
				->setParameter('user', $user)
				->orderBy('t.id', 'ASC')
				->setMaxResults(10)
				->getQuery()
				->getResult();
		}
	}
