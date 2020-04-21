<?php

namespace App\Repository;

use App\Entity\Referential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Transliterator;

/**
 * @method Referential|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referential|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referential[]    findAll()
 * @method Referential[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referential::class);
    }

    public function whereIn(string $referential, array $ids): QueryBuilder
    {
        return $this->createQueryBuilder('r', 'r.refId')
            ->where('r.type = :referential')
            ->setParameter(':referential', $referential)
            ->andWhere('r.refId IN (:ids)')
            ->setParameter('ids', $ids);
    }

    public function fullTextSearch(string $search_text): array
    {

        $transliterator = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC;');
        $searching_words = array_map(function ($word) use ($transliterator) {
            return $this->fullTextExpression($word, $transliterator);
        }, preg_split("/\s|, /", $search_text));
        $searching_words = array_filter($searching_words);
        $searching = implode(' ', $searching_words);

        // TODO
        // $referential_table_name = get_class(Referential::class);
        $sql = "
            SELECT * FROM referential
            WHERE MATCH (ref_id,label) AGAINST (:searching IN BOOLEAN MODE)
        ";

        try {
            $statement = $this->getEntityManager()->getConnection()->prepare($sql);
            $statement->execute(['searching' => $searching]);
            return $statement->fetchAll();
        } catch (DBALException $e) {
            dump($e);
        }
    }

    private function fullTextExpression(string $word, Transliterator $transliterator): ?string
    {
        $word = addslashes(mb_strtolower($transliterator->transliterate($word)));
        if (false === empty($word)) {
            if (1 === preg_match('/[\+\-\~\*]/', $word)) {
                return '+"' . $word . '" ';
            } else if (strpos($word, "'")) {
                return '+(' . $word . '*) ';
            } else {
                return '+' . $word . '* ';
            }
        }

        return null;
    }
}
