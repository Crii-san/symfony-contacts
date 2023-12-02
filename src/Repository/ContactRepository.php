<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function search(string $text = ''): array
    {
        $qb = $this->createQueryBuilder('cont');
        $qb->select('cont')
            ->addSelect('category')
            ->leftJoin('cont.category', 'category')
            ->where($qb->expr()->orX(
                $qb->expr()->like('cont.firstname', ':text'),
                $qb->expr()->like('cont.lastname', ':text')
            ))
            ->setParameter('text', '%'.$text.'%')
            ->orderBy('cont.lastname', 'ASC')
            ->addOrderBy('cont.firstname', 'ASC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findWithCategory(int $id): ?Contact
    {
        $qb = $this->createQueryBuilder('contact');
        $qb->select('contact')
            ->addSelect('category')
            ->leftJoin('contact.category', 'category')
            ->where('contact.id = :contactId')
            ->setParameter('contactId', "{$id}");

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    //    /**
    //     * @return Contact[] Returns an array of Contact objects
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

    //    public function findOneBySomeField($value): ?Contact
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
