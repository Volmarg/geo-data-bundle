<?php

namespace GeoTool\Dto\Internal;

/**
 * Contains api credentials
 */
class ApiCredentials
{
    /**
     * Can be null because some APIs require only API_KEY as password
     * @var string|null
     */
    private ?string $login;

    /**
     * @var string $password
     */
    private string $password;

    /**
     * @var string $domain
     */
    private string $domain;

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @param string $login
     * @param string $password
     * @param string $domain
     */
    public function __construct(string $login, string $password, string $domain)
    {
        $this->login    = $login;
        $this->password = $password;
        $this->domain   = $domain;
    }

}