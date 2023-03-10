<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function readAllByPost( int $id): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare('
            SELECT c.id, c.body, c.created_at, u.email
            FROM comment c
            JOIN user u ON c.user_id = u.id
            WHERE c.post_id = :id
            AND c.state = 5
            ORDER BY c.created_at DESC');
        $res = $stmt->executeQuery(['id' => $id]);

        // возвращает массив массивов (т.e. сырой набор данных)
        return $res->fetchAllAssociative();
    }

    public function addComment(array $values): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare('
            INSERT INTO comment
            (body, created_at, post_id, user_id, state)
            VALUES
            (:body, :created_at, :post_id, :user_id, :state)');
        $res = $stmt->executeQuery($values);

        return $conn->lastInsertId();
    }

    public function readAllByState( int $st, string $d1, string $d2): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare('
            SELECT c.id, c.body, c.created_at, u.email
            FROM comment c
            JOIN user u ON c.user_id = u.id
            WHERE c.state = :st
            AND c.created_at between :d1 and :d2');
        $res = $stmt->executeQuery(['st' => $st, 'd1' => $d1, 'd2' => $d2]);

        // возвращает массив массивов (т.e. сырой набор данных)
        return $res->fetchAllAssociative();
    }

//    /**
//     * @return Comment[] Returns an array of Comment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
