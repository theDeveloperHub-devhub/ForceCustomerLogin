<?php

namespace DevHub\ForceCustomerLogin\Helper\Strategy;

class StrategyManager
{
    /*
     * Fallback
     */
    const DEFAULT_STRATEGY = 'default';

    /**
     * @var StrategyInterface[]
     */
    private $strategies;
    /**
     * @var string[]
     */
    private $strategyNames;

    /**
     * LoginRequiredOnVisitorInitObserver constructor.
     *
     * @param StrategyInterface[] $strategies
     */
    public function __construct(array $strategies)
    {
        foreach ($strategies as $identifier => $strategyEntry) {
            $this->strategies[$identifier] = $strategyEntry;
            $this->strategyNames[$identifier] = $strategyEntry->getName();
        }
    }

    /**
     * @param string $identifier
     * @return bool
     */
    public function has($identifier)
    {
        return isset($this->strategies[$identifier]);
    }

    /**
     * @param string $identifier
     * @return StrategyInterface
     */
    public function get($identifier)
    {
        if (!isset($this->strategies[$identifier])) {
            if (isset($this->strategies[self::DEFAULT_STRATEGY])) {
                return $this->strategies[self::DEFAULT_STRATEGY];
            }
            throw new \InvalidArgumentException(
                sprintf(
                    'Could not load rule strategy with identifier "%s"',
                    $identifier
                )
            );
        }

        return $this->strategies[$identifier];
    }

    /**
     * @return StrategyInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }

    /**
     * @return string[]
     */
    public function getStrategyNames()
    {
        return $this->strategyNames;
    }
}
