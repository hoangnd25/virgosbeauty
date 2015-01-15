<?php

namespace VB\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 * @ORM\Table(name="redirect_rule")
 * @ORM\Entity()
 */
class RedirectRule {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(length=200,nullable = true)
     */
    protected $matchingUri;

    /**
     * @ORM\Column(length=200,nullable = true)
     */
    protected $redirectUri;

    /**
     * @ORM\Column(type="integer", nullable = false)
     */
    protected $statusCode;

    function __construct($matchingUri = null, $redirectUri = null, $statusCode = 301)
    {
        $this->matchingUri = $matchingUri;
        $this->redirectUri = $redirectUri;
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $matchingUri
     */
    public function setMatchingUri($matchingUri)
    {
        $this->matchingUri = $matchingUri;
    }

    /**
     * @return mixed
     */
    public function getMatchingUri()
    {
        return $this->matchingUri;
    }

    /**
     * @param mixed $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
}