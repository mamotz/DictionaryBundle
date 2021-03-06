<?php

namespace Knp\DictionaryBundle\Dictionary\Factory;

use InvalidArgumentException;
use Knp\DictionaryBundle\Dictionary;
use Knp\DictionaryBundle\Dictionary\Factory;
use Knp\DictionaryBundle\Dictionary\SimpleDictionary;
use Knp\DictionaryBundle\Dictionary\ValueTransformer;

class Value implements Factory
{
    /**
     * @var ValueTransformer
     */
    protected $transformer;

    /**
     * @param ValueTransformer $transformer
     */
    public function __construct(ValueTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     *
     * @throw InvalidArgumentException Not able to create a dictionary with the given name
     */
    public function create(string $name, array $config): Dictionary
    {
        if (!isset($config['content'])) {
            throw new InvalidArgumentException(sprintf(
                'The key content for dictionary %s must be set',
                $name
            ));
        }

        $content = $config['content'];
        $values  = [];

        foreach ($content as $value) {
            $values[] = $this->transformer->transform($value);
        }

        return new SimpleDictionary($name, $values);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $config): bool
    {
        return (isset($config['type'])) ? 'value' === $config['type'] : false;
    }
}
