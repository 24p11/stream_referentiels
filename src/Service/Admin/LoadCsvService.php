<?php


namespace App\Service\Admin;


use App\Entity\Referential;
use App\Entity\ReferentialType;
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

    public function toRepositories(UploadedFile $uploadedFile, ReferentialType $referentialType): array
    {
        return array_map(
            function ($item) use ($referentialType) {
                $repositories = new Referential();
                $repositories->setRefId($item[0]);
                $repositories->setLabel($item[1]);
                $repositories->setStartDate(ReferentialUtil::date($item[2]) ?? ReferentialUtil::date());
                $repositories->setEndDate(ReferentialUtil::date($item[3]) ?? null);
                $repositories->setType($referentialType);

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
        $csvData = array_map([$this, 'lineToArray'], explode("\n", file_get_contents($file)));
        unlink($file);

        return $csvData;
    }

    private function lineToArray(string $line)
    {
        return str_getcsv($line, ';');
    }
}
