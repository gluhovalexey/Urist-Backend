<?php

namespace App\UristBundle\Entity;

/**
 * Category
 */
class Category
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var integer
     */
    private $lft;

    /**
     * @var integer
     */
    private $rgt;

    /**
     * @var integer
     */
    private $lvl;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \App\UristBundle\Entity\Category
     */
    private $root;

    /**
     * @var \App\UristBundle\Entity\Category
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $service;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $document;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->service = new \Doctrine\Common\Collections\ArrayCollection();
        $this->document = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Add child
     *
     * @param \App\UristBundle\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(\App\UristBundle\Entity\Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \App\UristBundle\Entity\Category $child
     */
    public function removeChild(\App\UristBundle\Entity\Category $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set root
     *
     * @param \App\UristBundle\Entity\Category $root
     *
     * @return Category
     */
    public function setRoot(\App\UristBundle\Entity\Category $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \App\UristBundle\Entity\Category
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent
     *
     * @param \App\UristBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\App\UristBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \App\UristBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add service
     *
     * @param \App\UristBundle\Entity\Service $service
     *
     * @return Category
     */
    public function addService(\App\UristBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \App\UristBundle\Entity\Service $service
     */
    public function removeService(\App\UristBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get service
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Add document
     *
     * @param \App\UristBundle\Entity\Document $document
     *
     * @return Category
     */
    public function addDocument(\App\UristBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \App\UristBundle\Entity\Document $document
     */
    public function removeDocument(\App\UristBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get document
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocument()
    {
        return $this->document;
    }
}
