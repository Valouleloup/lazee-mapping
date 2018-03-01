<?php

namespace AppBundle\Command;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LazeeMappingCommand extends ContainerAwareCommand
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    public function __construct()
    {
        $this->reader = new AnnotationReader();

        parent::__construct();
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:lazee-mapping:generate')
            ->setDescription('Mapping associations');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $meta = $this->getContainer()->get('doctrine')->getManager()->getMetadataFactory()->getAllMetadata();

        $classes = [];

        foreach ($meta as $item) {
            if (false !== strpos($item->name, 'Entity')) {
                $classes[] = $item->name;
            }
        }

        $documentation = '';

        foreach ($classes as $class) {
            $associations = $this->getClassAssociations($class);

            $documentation .= $this->formatDocumentation($class, $associations);
        }

        $output->write($documentation);
    }

    private function getClassAssociations(string $classFullname)
    {
        $reflection = new \ReflectionClass($classFullname);
        $properties = $reflection->getProperties();

        return $this->getAssociationsFromProperties($properties);
    }

    private function getAssociationsFromProperties(array $properties)
    {
        $links = [];

        foreach ($properties as $property) {
            $link = null;

            if (null !== $link = $this->reader->getPropertyAnnotation($property, 'Doctrine\\ORM\\Mapping\\OneToMany')) {
                $links[] = [
                    'type' => 'OneToMany',
                    'target' => $link->targetEntity,
                ];
            }

            if (null !== $link = $this->reader->getPropertyAnnotation($property, 'Doctrine\\ORM\\Mapping\\ManyToOne')) {
                $links[] = [
                    'type' => 'ManyToOne',
                    'target' => $link->targetEntity,
                ];
            }

            if (null !== $link = $this->reader->getPropertyAnnotation($property, 'Doctrine\\ORM\\Mapping\\OneToOne')) {
                $links[] = [
                    'type' => 'OneToOne',
                    'target' => $link->targetEntity,
                ];
            }

            if (null !== $link = $this->reader->getPropertyAnnotation($property, 'Doctrine\\ORM\\Mapping\\ManyToMany')) {
                $links[] = [
                    'type' => 'ManyToMany',
                    'target' => $link->targetEntity,
                ];
            }
        }

        return $links;
    }

    private function formatDocumentation(string $class, array $associations)
    {
        $documentation = '### ' . $class . PHP_EOL;

        foreach ($associations as $association) {
            if (false === strpos($association['target'], '\\')) {
                $targetClassName = $association['target'];
            } else {
                $targetClass = new \ReflectionClass($association['target']);
                $targetClassName = $targetClass->getShortName();
            }

            if ('OneToOne' === $association['type'] || 'ManyToOne' === $association['type']) {
                $documentation .= '- `' . $association['type'] . '` ' . $targetClassName . ' : ' . $class . ' est lié à un seul `' . $targetClassName . '`' . PHP_EOL;
            } else {
                $documentation .= '- `' . $association['type'] . '` ' . $targetClassName . ' : ' . $class . ' est lié à plusieurs `' . $targetClassName . '`' . PHP_EOL;
            }
        }

        $documentation .= PHP_EOL;

        return $documentation;
    }
}
