<?php

namespace App\UristBundle\Entity;

/**
 * Service
 */
class Service
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
    private $price;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Service
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
     * Set price
     *
     * @param string $price
     *
     * @return Service
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add category
     *
     * @param \App\UristBundle\Entity\Category $category
     *
     * @return Service
     */
    public function addCategory(\App\UristBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \App\UristBundle\Entity\Category $category
     */
    public function removeCategory(\App\UristBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @var string
     */
    private $slug;

    /**
    * [setCategories set collection of category entity]
    * @param [type] $categories [categories slug]
    */
    public function setCategories($categories)
    {
        if (isset($categories) && is_array($categories) && count($categories)) {
            foreach ($categories as $category){
                $this->addCategory($category);
            }
        }
    }

    /**
     * Синхронизация связи с категориями
     * @param $categoriesUpd Сущности связанных категорий
     */
    public function syncCategories($categoriesUpd)
    {

        $categories = $this->getCategory();

        // Проверяем есть ли категория из данных среди имеющихся, если не, то добавляем
        foreach ($categoriesUpd as $category) {
            // if ( !$categories->contains($category) ){
            if (!in_array($category, $categories)){
                $this->addCategory($category);
            }
        }
        // Проверяем есть ли у имеюхся категорий, категории из данных, если нет то удаляем
        foreach ($categories as $category) {
            if (!in_array($category, $categoriesUpd)) {
                $this->removeCategory($category);
            }
        }
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Service
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
}
