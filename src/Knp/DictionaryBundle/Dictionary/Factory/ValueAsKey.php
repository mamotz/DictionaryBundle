<?php

namespace Knp\DictionaryBundle\Dictionary\Factory;

use InvalidArgumentException;
use Knp\DictionaryBundle\Dictionary;
use Knp\DictionaryBundle\Dictionary\Factory;
use Knp\DictionaryBundle\Dictionary\SimpleDictionary;
use Knp\DictionaryBundle\Dictionary\ValueTransformer;

class ValueAsKey implements Factory
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
     * @throw InvalidArgumentException if there is some problem with the config.
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
            $builtValue          = $this->transformer->transform($value);
            $values[$builtValue] = $builtValue;
        }

        return new SimpleDictionary($name, $values);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $config): bool
    {
        return (isset($config['type'])) ? 'value_as_key' === $config['type'] : false;
    }
}
