<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\File;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<File>
 *
 * @method File|null find($id, $lockMode = null, $lockVersion = null)
 * @method File|null findOneBy(array $criteria, array $orderBy = null)
 * @method File[]    findAll()
 * @method File[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function add(File $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(File $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return File[] Returns an array of File objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findByUserAndCategory($user_login, $category_label): array|float|int|string
   {
        return $this->createQueryBuilder('f')
            ->innerJoin(
                User::class,
                'u',
                Join::WITH,
                'u.id = f.user'
            )
            ->andWhere('u.login = :user_login')
            ->setParameter('user_login', $user_login)
            ->innerJoin(
                Category::class,
                'c',
                Join::WITH,
                'c.id = f.category'
            )
            ->andWhere('c.label = :category_id')
            ->setParameter('category_id', $category_label)
            ->orderBy('f.created_at', 'desc')
            ->getQuery()
            ->getArrayResult()
            ;
    }
}
