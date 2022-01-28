<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    /**
     * Moins chers
     * @param integer $number Nombre d'articles à afficher
     * @return array
     */
    public function findLessPrice(int $number): array
    {
        if ($number <= 0)
        {
            return [];
        }

        return $this->createQueryBuilder('p')
            ->addOrderBy('p.price', 'ASC')
            ->addOrderBy('p.name', 'ASC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récents
     * @param integer $number Nombre d'articles à afficher
     * @return array
     */
    public function findMostRecent(int $number): array
    {
        if ($number <= 0)
        {
            return [];
        }
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.createdAt', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
    }
}
