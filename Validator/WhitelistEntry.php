<?php

namespace DevHub\ForceCustomerLogin\Validator;

use DevHub\ForceCustomerLogin\Model\WhitelistEntry as WhitelistEntryModel;

class WhitelistEntry
{
    /**
     * @param WhitelistEntryModel $whitelistEntry
     * @return bool
     * @throw \InvalidArgumentException If validation fails
     */
    public function validate(
        WhitelistEntryModel $whitelistEntry
    ) {
        $label = \strlen(\trim((string)$whitelistEntry->getLabel()));
        if (0 === $label) {
            throw new \InvalidArgumentException('Label value is too short.');
        }
        if (255 < $label) {
            throw new \InvalidArgumentException('Label value is too long.');
        }

        $urlRule = \strlen(\trim((string)$whitelistEntry->getUrlRule()));
        if (0 === $urlRule) {
            throw new \InvalidArgumentException('Url Rule value is too short.');
        }
        if (255 < $urlRule) {
            throw new \InvalidArgumentException('Url Rule value is too long.');
        }

        $strategy = \strlen(\trim((string)$whitelistEntry->getStrategy()));
        if (0 === $strategy) {
            throw new \InvalidArgumentException('Strategy value is too short.');
        }
        if (255 < $strategy) {
            throw new \InvalidArgumentException('Strategy value is too long.');
        }

        if (!\is_bool($whitelistEntry->getEditable())) {
            throw new \InvalidArgumentException('Editable is no boolean value.');
        }

        return true;
    }
}
