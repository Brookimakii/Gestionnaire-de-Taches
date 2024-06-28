<?php

	namespace App\Repository;

	use App\Entity\TaskList;
	use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
	use Doctrine\Persistence\ManagerRegistry;
	use function Doctrine\ORM\QueryBuilder;

	/**
	 * @extends ServiceEntityRepository<TaskList>
	 */
	class TaskListRepository extends ServiceEntityRepository {

		public function __construct(ManagerRegistry $registry) {
			parent::__construct($registry, TaskList::class);
		}

//    /**
//     * @return TaskList[] Returns an array of TaskList objects
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
		/**
		 * @return TaskList[] Returns an array of TaskList objects
		 */
		public function findPersonalListOfUser($owner): array {
			return $this->createQueryBuilder('tl')
				->leftJoin('tl.sharedWith', 's')
				->andWhere('tl.owner = :userId')
				->andWhere('s.id IS NULL')
				->setParameter('userId', $owner)
				->orderBy('tl.id', 'ASC')
//				->setMaxResults(10)
				->getQuery()
				->getResult();
		}

		/**
		 * @return TaskList[] Returns an array of TaskList objects
		 */
		public function findSharedListOfUser($owner): array {
			return $this->createQueryBuilder('tl')
				->leftJoin('tl.sharedWith', 's')
				->andWhere('(tl.owner = :userId AND s.id IS NOT NULL) OR s.id = :userId')
				->setParameter('userId', $owner)
				->orderBy('tl.id', 'ASC')
//				->setMaxResults(10)
				->getQuery()
				->getResult();
		}

//    public function findOneBySomeField($value): ?TaskList
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
	public function searchByQuery($query)
		{
			return $this->createQueryBuilder('t')
				->andWhere('t.title LIKE :query OR t.description LIKE :query')
				->setParameter('query', '%' . $query . '%')
				->getQuery()
				->getResult();
		}
	}
