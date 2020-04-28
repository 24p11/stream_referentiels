<?php

namespace App\Repository;

use App\Entity\Referential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Transliterator;

class ReferentialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referential::class);
    }

    public function fullTextSearch(string $type, string $search_text, string $startDate, string $endDate): array
    {

        $transliterator = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC;');
        $searchingWords = array_map(function ($word) use ($transliterator) {
            return $this->fullTextExpression($word, $transliterator);
        }, preg_split("/\s|, /", $search_text));
        $searchingWords = array_filter($searchingWords);
        $searching = implode(' ', $searchingWords);


        return $this->createQueryBuilder('r')
            ->select([
                'r', 'm', 't'
            ])
            ->join('r.type', 't')
            ->leftJoin('r.metadata', 'm')
            ->where('MATCH_AGAINST (r.refId, r.label) AGAINST (:searching  boolean) > 0')
            ->andWhere('r.type = :type')
            ->orderBy('r.score', 'DESC')
            ->setMaxResults(250)
            ->setParameter('type', $type)
            ->setParameter('searching', $searching)
            ->getQuery()
            ->getResult();
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

    private function fullTextQuery($start_date, $end_date): string
    {
        // TODO
        // $referential_table_name = get_class(Referential::class);
        $start_date = $this->escape($start_date);
        $end_date = $this->escape($end_date);
        $has_date = !empty($start_date) && !empty($end_date);
        $metadata_date_condition = $has_date
            ? "(m.start_date >= '$start_date' AND IFNULL(m.end_date, CURDATE()) <= '$end_date')"
            : 'CURDATE() BETWEEN m.start_date AND IFNULL(m.end_date, CURDATE())';
        $referential_date_condition = $has_date
            ? "(r.start_date >= '$start_date' AND IFNULL(r.end_date, CURDATE()) <= '$end_date')"
            : "CURDATE() BETWEEN r.start_date AND IFNULL(r.end_date, CURDATE())";

        return "
            SELECT *, 
            r.id as id,
            r.start_date as referential_start_date, 
            r.end_date as referential_end_date, 
            r.created_at as referential_created_at, 
            r.updated_at as referential_updated_at
            FROM referential r
            LEFT JOIN metadata m 
            ON m.referential_id = r.id AND $metadata_date_condition
            WHERE r.`type` = :type
            AND $referential_date_condition
            AND MATCH (r.ref_id, r.label) AGAINST (:searching IN BOOLEAN MODE)
            ORDER BY r.score DESC
            LIMIT 250
            ";
    }

    private function escape(string $param): string
    {
        return addslashes(htmlentities($param));
    }
}
