<?php

namespace App\UristBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\UristBundle\Utils\UristUtils;
/**
 * Document
 */
class Document
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var file
     */
    private $file;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $path;

    /**
     * @var $oldPath
     */
    
    private $oldPath;

    /**
     * @var string
     */
    private $temp;

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
     * Set name
     *
     * @param string $name
     *
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Document
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
     * Set path
     *
     * @param string $path
     *
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Add category
     *
     * @param \App\UristBundle\Entity\Category $category
     *
     * @return Document
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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path
        ? null
        : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
        ? null
        : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../Resources/public/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'documents';
    }

     /**
     * Get file.
     *
     * @return UploadedFile
     */
     public function getFile()
     {
        return $this->file;
    }

    /**
     * @ORM\PrePersist
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $this->setName($this->getFile()->getClientOriginalName());
            $this->path = UristUtils::getUrlFromTitle($this->name).'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeUpdateAction()
    {                   
        $path_parts = pathinfo($this->getAbsolutePath());
        if ($path_parts['basename'] !== $this->name){
            $this->oldPath = $this->path;
            $this->path = UristUtils::getUrlFromTitle($this->name).'.'.$path_parts['extension'];
        }
    }

    /**
     * @ORM\PostPersist
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostUpdate
     */
    public function onUpdateAction()
    {
        if ($this->oldPath) 
        {
            rename($this->getUploadRootDir().'/'.$this->oldPath, $this->getAbsolutePath());
        }
    }

    /**
     * @ORM\PostRemove
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
}
