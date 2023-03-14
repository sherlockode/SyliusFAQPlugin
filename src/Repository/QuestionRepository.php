<?php

namespace Sherlockode\SyliusFAQPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class QuestionRepository extends EntityRepository
{
    /**
     * @param ChannelInterface $channel
     *
     * @return array
     */
    public function findByChannel(ChannelInterface $channel): array
    {
        return $this->createQueryBuilder('question')
            ->join('question.channels', 'question_channels')
            ->where('question_channels = :channel')
            ->orderBy('question.position', 'ASC')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getResult()
            ;
    }
}
