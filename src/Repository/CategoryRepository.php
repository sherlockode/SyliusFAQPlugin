<?php

namespace Sherlockode\SyliusFAQPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class CategoryRepository extends EntityRepository
{
    /**
     * @param string $locale
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(string $locale): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->setParameter('locale', $locale)
        ;
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return array
     */
    public function findByChannel(ChannelInterface $channel): array
    {
        return $this->createQueryBuilder('category')
            ->addSelect('questions')
            ->join('category.questions', 'questions')
            ->join('questions.channels', 'question_channels')
            ->join('category.channels', 'category_channels')
            ->where('category_channels = :channel')
            ->andWhere('question_channels = :channel')
            ->orderBy('category.position', 'ASC')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getResult()
        ;
    }
}
