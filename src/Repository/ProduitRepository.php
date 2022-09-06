<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getProduitsByUser(User $user)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.auteur = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderAsc()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getProduitsByName($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :val')
            ->setParameter('val', "%$value%")
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
