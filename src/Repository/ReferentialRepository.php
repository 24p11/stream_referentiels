<?php

namespace App\Repository;

use App\Entity\Referential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Transliterator;

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

    public function fullTextSearch(string $type, string $search_text): array
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
            SELECT *, 
            r.id as id,
            r.start_date as referential_start_date, 
            r.end_date as referential_end_date, 
            r.created_at as referential_created_at, 
            r.updated_at as referential_updated_at
            FROM referential r
            LEFT JOIN metadata m 
            ON m.referential_id = r.id AND CURDATE() BETWEEN m.start_date AND IFNULL(m.end_date, CURDATE())
            WHERE r.`type` = ?
            AND CURDATE() BETWEEN r.start_date AND IFNULL(r.end_date, CURDATE())
            AND MATCH (r.ref_id, r.label) AGAINST (? IN BOOLEAN MODE)
            ORDER BY r.score DESC
            LIMIT 250
        ";

        try {
            $statement = $this->getEntityManager()->getConnection()->prepare($sql);
            $statement->execute([
                $type,
                $searching
            ]);

            $repositories = $statement->fetchAll();
            return array_values(array_reduce($repositories, function (array $accumulator, array $referential) {
                return $this->groupByRefId($accumulator, $referential);
            }, []));
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

    private function groupByRefId(array $accumulator, array $referential)
    {
        $referential_key = $referential['ref_id'];
        $metadata_key = 'metadata';
        if (isset($accumulator[$referential_key])) {
            $accumulator[$referential_key][$metadata_key][] = [
                $referential['entry'] => $referential['value'],
                'start_date' => $referential['start_date'],
                'end_date' => $referential['end_date'],
            ];
        } else {
            $accumulator[$referential_key] = [
                'id' => $referential['id'],
                'type' => $referential['type'],
                'ref_id' => $referential_key,
                'label' => $referential['label'],
                'start_date' => $referential['referential_start_date'],
                'end_date' => $referential['referential_end_date'],
                'created_at' => $referential['referential_created_at'],
                'updated_at' => $referential['referential_updated_at'],
            ];

            // If metadata
            if ($referential['entry']) {
                $accumulator[$referential_key][$metadata_key][] = [
                    $referential['entry'] => $referential['value'],
                    'start_date' => $referential['start_date'],
                    'end_date' => $referential['end_date'],
                ];
            }
        }

        return $accumulator;
    }
}
