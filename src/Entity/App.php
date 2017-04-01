<?php

namespace AlgoliaApp\Entity;

class App implements EntityInterface
{
    /**
     * List of fields with the corresponding setter methods. They are all
     * required. If something more evolved and automatic ever needs to be used
     * for setters, use ucwords() or some lib that will transform field names
     * into camelcase
     * @var array
     */
    private $fields = [
        'name' => 'setName',
        'image' => 'setImage',
        'link' => 'setLink',
        'category' => 'setCategory',
        'rank' => 'setRank',
    ];

    private $name;
    private $image;
    private $link;
    private $category;
    private $rank;
    private $objectId;

    public function __construct(array $data)
    {
        foreach ($this->fields as $key => $method) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException('Key is required but was not found: ' . $key);
            }

            $this->$method($data[$key]);
        }
    }

    public function getAllData()
    {
        $data = [
            'name' => $this->getName(),
            'image' => $this->getImage(),
            'link' => $this->getLink(),
            'category' => $this->getCategory(),
            'rank' => $this->getRank(),
        ];

        return $data;
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
            throw new \InvalidArgumentException('Name cannot be empty');
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
            throw new \InvalidArgumentException('Image cannot be empty');
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
            throw new \InvalidArgumentException('Link cannot be empty');
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
            throw new \InvalidArgumentException('Category cannot be empty');
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
            throw new \InvalidArgumentException('Rank cannot be empty');
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
