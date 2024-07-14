<?php

namespace GeoTool\Dto\Nominatim;

class AllData
{
    public function __construct(
        private readonly ?DetailsResult $detailsResult = null,
        private readonly ?SearchResult  $searchResult = null
    ){}

    /**
     * @return DetailsResult|null
     */
    public function getDetailsResult(): ?DetailsResult
    {
        return $this->detailsResult;
    }

    /**
     * @return SearchResult|null
     */
    public function getSearchResult(): ?SearchResult
    {
        return $this->searchResult;
    }

    /**
     * @return bool
     */
    public function hasDetails(): bool
    {
        return !empty($this->getDetailsResult());
    }

    /**
     * @return bool
     */
    public function hasSearchResult(): bool
    {
        return !empty($this->getSearchResult());
    }

}
