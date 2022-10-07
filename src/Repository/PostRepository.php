<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


    public function findByTitle( $tpl): ?array
    {
/*
        $query = $this->getEntityManager()->createQuery(
            'SELECT p.id, p.title, p.body, p.created_at, u.email
            FROM App\Entity\Post p
            JOIN p.user u
            WHERE p.title LIKE :query')
            ->setHint(Query::HINT_READ_ONLY, true)
            ->setParameter('query', '%'. $tpl. '%');

        return $query->getScalarResult();
*/
        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare('
            SELECT p.id, p.title, p.intro, p.created_at, u.email
            FROM post p
            JOIN user u ON p.user_id = u.id
            WHERE p.title LIKE :q');
        $res = $stmt->executeQuery(['q' => '%'. $tpl. '%']);

        // возвращает массив массивов (т.e. сырой набор данных)
        return $res->fetchAllAssociative();

    }

    public function readOneJoined( int $id): ?array
    {
/*
        $query = $this->getEntityManager()->createQuery(
            'SELECT p.id, p.title, p.body, p.created_at, p.user_id, u.email
            FROM App\Entity\Post p
            JOIN p.user u
            WHERE p.id = :id')
            ->setParameter('id', $id);

//        return $query->getOneOrNullResult(Query::HYDRATE_SCALAR);
        return $query->getScalarResult()[0];
*/
        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare('
            SELECT p.id, p.title, p.intro, p.body, p.created_at, p.user_id, u.email
            FROM post p
            JOIN user u ON p.user_id = u.id
            WHERE p.id = :id');
        $res = $stmt->executeQuery(['id' => $id]);
        if ($res->rowCount() === 0) {
            return null;
        }

        // возвращает массив массивов (т.e. сырой набор данных)
        return $res->fetchAllAssociative()[0];
    }

    public function readAllJoined(): ?array
    {
/*
        $query = $this->getEntityManager()->createQuery(
            'SELECT p.id, p.title, p.body, p.created_at, u.email
            FROM App\Entity\Post p
            JOIN p.user u');

        return $query->getScalarResult();
*/
        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare('
            SELECT p.id, p.title, p.intro, p.created_at, u.email
            FROM post p
            JOIN user u ON p.user_id = u.id');
        $res = $stmt->executeQuery();

        // возвращает массив массивов (т.e. сырой набор данных)
        return $res->fetchAllAssociative();
    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
