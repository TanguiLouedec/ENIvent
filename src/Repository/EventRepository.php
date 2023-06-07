<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\HomeFilter;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Event::class);
        $this->security = $security;
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByFilters(FormInterface $filterForm)
    {
        $keywords = $filterForm->get('searchField')->getData();
        $campus = $filterForm->get('campus')->getData();
        $estOrganisateur = $filterForm->get('isOrganised')->getData();
        $estInscrit = $filterForm->get('isSubed')->getData();
        $pasInscrit = $filterForm->get('isNotSubed')->getData();
        $sortiesPassees = $filterForm->get('isOver')->getData();
        $dateMin = $filterForm->get('startDate')->getData();
        $dateMax = $filterForm->get('endDate')->getData();

        $qb = $this->createQueryBuilder('s');

        if ($keywords) {
            $qb->where($qb->expr()->like('s.nom', ':keywords'))
                ->setParameter('keywords', '%' . $keywords . '%');
        }

        if ($campus) {
            $qb->andWhere('s.campus =:campus')
                ->setParameter('campus', $campus);
        }

        if ($estOrganisateur) {
            $currentUser = $this->security->getUser();
            $qb->andWhere('s.organisateur =:currentUser')
                ->setParameter('currentUser', $currentUser);
        }

        if ($estInscrit) {
            $currentUser = $this->security->getUser();
            $qb->select('s')
                ->from(Sortie::class, 'sc')
                ->leftJoin('s.organisateur', 'o')
                ->leftJoin('s.utilisateurs', 'i')
                ->andWhere($qb->expr()->eq('i', ':currentUser'))
                ->setParameter('currentUser', $currentUser);

        }
        if ($pasInscrit) {
            $currentUser = $this->security->getUser();
            $subQueryBuilder = $this->createQueryBuilder('sub')
                ->select('sub.id')
                ->leftJoin('sub.utilisateurs', 'subUser')
                ->andWhere('subUser = :currentUser')
                ->setParameter('currentUser', $currentUser);

            $qb->select('s')
                ->from(Sortie::class, 'sc')
                ->leftJoin('s.organisateur', 'o')
                ->andWhere($qb->expr()->notIn('s.id', $subQueryBuilder->getDQL()))
                ->setParameter('currentUser', $currentUser);
        }

        if ($sortiesPassees){
            $qb->select('s')
                ->leftJoin('s.etat', 'etat')
                ->andWhere('etat.id =:etatPasse')
                ->setParameter('etatPasse', 5);
        }
        if ($dateMin) {
            $qb->andWhere('s.dateHeureDebut >= :dateMin')
                ->setParameter('dateMin', $dateMin);
        }
        if($dateMax){
            $qb->andWhere('s.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $dateMax);
        }

        return $qb->getQuery()->getResult();
    }
//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
