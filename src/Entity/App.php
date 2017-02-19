<?php

namespace AlgoliaApp\Entity;

class App
{
    private $name;
    private $image;
    private $link;
    private $category;
    private $rank;
    private $objectId;

    public function __construct(array $data)
    {
        $this->setName($data['name']);
        $this->setImage($data['image']);
        $this->setLink($data['link']);
        $this->setCategory($data['category']);
        $this->setRank($data['rank']);
    }
    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        if (empty($name)) {
            throw new \Exception('Name is required');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of image.
     *
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the value of image.
     *
     * @param mixed $image the image
     *
     * @return self
     */
    public function setImage($image)
    {
        if (empty($image)) {
            throw new \Exception('Image is required');
        }

        $this->image = $image;

        return $this;
    }

    /**
     * Gets the value of link.
     *
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the value of link.
     *
     * @param mixed $link the link
     *
     * @return self
     */
    public function setLink($link)
    {
        if (empty($link)) {
            throw new \Exception('Link is required');
        }

        $this->link = $link;

        return $this;
    }

    /**
     * Gets the value of category.
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the value of category.
     *
     * @param mixed $category the category
     *
     * @return self
     */
    public function setCategory($category)
    {
        if (empty($category)) {
            throw new \Exception('Category is required');
        }

        $this->category = $category;

        return $this;
    }

    /**
     * Gets the value of rank.
     *
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Sets the value of rank.
     *
     * @param mixed $rank the rank
     *
     * @return self
     */
    public function setRank($rank)
    {
        if (empty($rank)) {
            throw new \Exception('Rank is required');
        }

        $this->rank = $rank;

        return $this;
    }

    /**
     * Gets the value of objectId.
     *
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Sets the value of objectId.
     *
     * @param mixed $objectId the object id
     *
     * @return self
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }
}
