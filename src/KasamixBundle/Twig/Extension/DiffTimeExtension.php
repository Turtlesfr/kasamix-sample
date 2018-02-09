<?php

namespace KasamixBundle\Twig\Extension;

class DiffTimeExtension extends \Twig_Extension
{
    public function getFilters(): array
    {
        return array(
            new \Twig_SimpleFilter('diff_time', array($this, 'calculateDiff')),
        );
    }

    public function calculateDiff($start, $end)
    {
        /** @var \DateTime $start */
        /** @var \DateInterval $diff */
        $diff = $start->diff($end);
        return $diff->format('%i minutes %s seconds');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'diff_time';
    }
}
