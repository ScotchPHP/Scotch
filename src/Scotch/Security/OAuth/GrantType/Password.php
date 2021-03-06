<?php
namespace Scotch\Security\OAuth\GrantType;

use Scotch\Security\OAuth\Exceptions\InvalidArgumentException as InvalidArgumentException;

/**
 * Password Parameters
 */
class Password implements IGrantType
{
    /**
     * Defines the Grant Type
     *
     * @var string  Defaults to 'password'.
     */
    const GRANT_TYPE = 'password';

    /**
     * Adds a specific Handling of the parameters
     *
     * @return array of Specific parameters to be sent.
     * @param  mixed  $parameters the parameters array (passed by reference)
     */
    public function validateParameters(&$parameters)
    {
        if (!isset($parameters['username']))
        {
            throw new InvalidArgumentException(
                'The \'username\' parameter must be defined for the Password grant type',
                InvalidArgumentException::MISSING_PARAMETER
            );
        }
        elseif (!isset($parameters['password']))
        {
            throw new InvalidArgumentException(
                'The \'password\' parameter must be defined for the Password grant type',
                InvalidArgumentException::MISSING_PARAMETER
            );
        }
    }
}
