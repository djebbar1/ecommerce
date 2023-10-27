<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }
    
        // public function findByCriteria($criteria)
        // {
        //     $queryBuilder = $this->createQueryBuilder('p')
        //         ->select('p');
    
        //     // Si le nom est spécifié dans les critères, ajoutez une clause WHERE pour filtrer par nom
        //     if ($criteria['name']) {
        //         $queryBuilder->andWhere('p.name LIKE :name')
        //             ->setParameter('name', '%' . $criteria['name'] . '%');
        //     }
    
        //     // Vous pouvez ajouter d'autres critères de recherche ici
    
        //     // Exécutez la requête
        //     return $queryBuilder->getQuery()->getResult();
        // }
       /**
     * @param string $searchTerm
     * @return Products[] Returns an array of Product objects
     */
    public function findBySearchTerm($searchTerm)
{
    return $this->createQueryBuilder('p')
        // ->select('p.name', 'p.image', 'p.description', 'p.price')
        ->where('p.name LIKE :searchTerm')
        ->setParameter('searchTerm', '%'.$searchTerm.'%')
        ->getQuery()
        ->getResult();
}
    public function configurationOptions(OptionsResolver $resolver){

    $resolver->setDefaults([
        'data_class' => Products::class,
        'method' => 'GET',
        'crsf_protection' => false
    ]);
    }
    public function findProduitsPaginated(int $page, int $slug, int $limit = 6): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
        ->select('c', 'p')
        ->from('App\Entity\Products', 'p')
        ->join('p.categories', 'c')
        ->where('c.id = :slug')
        ->setParameter('slug', $slug)
        ->setMaxResults($limit)
        ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        // On verifie qu'on a des données
        if(empty($data)){
            return $result;
        }

        // On calcule le nombre de pages
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau
        $result['data'] = $data;
        $result['page'] = $pages;
        $result['page'] = $page;
        $result['pages'] = $limit;

        return $result;

    }

//    /**
//     * @return Products[] Returns an array of Products objects
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

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
