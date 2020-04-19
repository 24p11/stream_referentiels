<?php


namespace App\Service\Admin;


use App\Entity\ReferentialTypes;
use App\Entity\Repositories;
use App\Util\ReferentialUtil;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadCsvService
{
    private $logger;
    private $uploadDirectory;

    /**
     * LoadCsvService constructor.
     * @param LoggerInterface $logger
     * @param $uploadDirectory
     */
    public function __construct(LoggerInterface $logger, $uploadDirectory)
    {
        $this->logger = $logger;
        $this->uploadDirectory = $uploadDirectory;
    }

    public function toRepositories(UploadedFile $uploadedFile, ReferentialTypes $referential): array
    {
        return array_map(
            function ($item) use ($referential) {
                $repositories = new Repositories();
                $repositories->setRefId($item[0]);
                $repositories->setLabel($item[1]);
                $repositories->setStartDate(ReferentialUtil::date($item[2]) ?? ReferentialUtil::date());
                $repositories->setEndDate(ReferentialUtil::date($item[3]) ?? null);
                $repositories->setType($referential);

                return $repositories;
            },
            $this->handleCsv($uploadedFile)
        );
    }

    private function handleCsv(UploadedFile $uploadedFile): array
    {
        $filename = $uploadedFile->getClientOriginalName();
        try {
            $uploadedFile->move(
                $this->uploadDirectory,
                $filename
            );
        } catch (FileException $e) {
            $this->logger->error("Error during '$filename' upload");
        }

        $file = $this->uploadDirectory . DIRECTORY_SEPARATOR . $filename;
        $csv_data = array_map([$this, 'lineToArray'], explode("\n", file_get_contents($file)));
        unlink($file);

        return $csv_data;
    }

    private function lineToArray(string $line)
    {
        return str_getcsv($line, ';');
    }
}