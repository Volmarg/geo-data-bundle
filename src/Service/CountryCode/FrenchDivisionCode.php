<?php

namespace GeoTool\Service\CountryCode;

use Exception;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * {@link https://service.unece.org/trade/locode/fr.htm}
 * The codes are used on a lot of french job offer services, where the location is not matched by name
 * but by this strange division id
 */
class FrenchDivisionCode
{

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(
        private readonly KernelInterface $kernel
    ) {
    }

    /**
     * Most of the codes are numeric but some consist of letters too, thus returning string.
     * Null means that no code was found for given location name
     *
     * @param string $searchedLocationName
     *
     * @return string|null
     *
     * @throws Exception
     */
    public function getDivCodeForLocationName(string $searchedLocationName): ?string
    {
        /** @var resource $pointer */
        $pointer = $this->readDataFile();
        while (($csvData = fgetcsv($pointer)) !== false) {
            $locationName = trim(mb_strtolower($csvData[0]));
            $code         = $csvData[1];

            if ($locationName === trim(mb_strtolower($searchedLocationName))) {
                return $code;
            }
        }

        return null;
    }

    /**
     * {@see self::getDivCodeForLocationName()}, but in here it returns just the number, null if no numbers are present
     * or no matching code was found
     *
     * @param string $searchedLocationName
     *
     * @return int|null
     *
     * @throws Exception
     */
    public function getDivNumber(string $searchedLocationName): ?int
    {
        $code = $this->getDivCodeForLocationName($searchedLocationName);
        if (empty($code)) {
            return null;
        }

        preg_match("#\d*#", $code, $matches);
        return $matches[0] ?? null;
    }

    /**
     * Keep in mind that the path is relative on purpose because kernel project root
     * will be the path of the project which uses this bundle, so calling kernel + dir name won't work here
     *
     * @return resource
     * @throws Exception
     */
    private function readDataFile(): mixed
    {
        $fileName = "french-unlocode.csv";

        // when calling the bundle itself
        $path = $this->kernel->getProjectDir() . "/data/{$fileName}";
        if (!file_exists($path)) {
            // when using this code as bundle
            $path = $this->kernel->getProjectDir() . "/vendor/volmarg/geo-data-bundle/data/{$fileName}";
        }

        $pointer = fopen($path, 'r');
        if (is_bool($pointer)) {
            throw new Exception("Could not read the french unlocode file");
        }

        return $pointer;
    }
}