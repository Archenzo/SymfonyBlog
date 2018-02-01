<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentsRepository")
 */
class Comments
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="readername", type="string", length=255, unique=true)
     */
    private $readername;

    /**
     * @var string
     *
     * @ORM\Column(name="readercomment", type="string", length=1000)
     */
    private $readercomment;

    /**
     * @var string
     *
     * @ORM\Column(name="articleid", type="string",length=255)
     */
    private $articleid;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set readername.
     *
     * @param string $readername
     *
     * @return Comments
     */
    public function setReadername($readername)
    {
        $this->readername = $readername;

        return $this;
    }

    /**
     * Get readername.
     *
     * @return string
     */
    public function getReadername()
    {
        return $this->readername;
    }

    /**
     * Set readercomment.
     *
     * @param string $readercomment
     *
     * @return Comments
     */
    public function setReadercomment($readercomment)
    {
        $this->readercomment = $readercomment;

        return $this;
    }

    /**
     * Get readercomment.
     *
     * @return string
     */
    public function getReadercomment()
    {
        return $this->readercomment;
    }

    /**
     * Set articleid.
     *
     * @param int $articleid
     *
     * @return Comments
     */
    public function setArticleid($articleid)
    {
        $this->articleid = $articleid;

        return $this;
    }

    /**
     * Get articleid.
     *
     * @return int
     */
    public function getArticleid()
    {
        return $this->articleid;
    }
}
