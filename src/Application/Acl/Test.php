<?php
namespace Osf\Application\Acl;

use Osf\Application\Acl;
use Osf\Test\Runner as OsfTest;

/**
 * Config manager unit tests
 *
 * @author Guillaume Ponçon <guillaume.poncon@openstates.com>
 * @copyright OpenStates
 * @version 1.0
 * @since OSF-2.0 - 14 sept. 2013
 * @package osf
 * @subpackage application
 */
class Test extends OsfTest
{
    public static function run()
    {
        self::reset();

        $acl = new Acl(__DIR__ . '/acl.php');
        self::assertEqual($acl->getRoles(), [], 'Not lazy');
        self::assert($acl->isAllowed('PUBLIC', 'common_index'));
        self::assert($acl->isAllowed('reader@test.com', 'common_index'));
        self::assertEqual($acl->getRoles(), [
            'PUBLIC', 'LOGGED', 'NOTLOGGED', 'ADMIN', 
            'reader@test.com', 'admin@test.com', 'otheradmin@test.com'
        ]);
        
        self::assert(!$acl->isAllowed('reader@test.com', 'login_index'));
        self::assert(!$acl->isAllowed('PUBLIC', 'login_index'));
        self::assert($acl->isAllowed('NOTLOGGED', 'login_index'));
        self::assert(!$acl->isAllowed('reader@test.com', 'login'));
        self::assert(!$acl->isAllowed('PUBLIC', 'login'));
        self::assert($acl->isAllowed('NOTLOGGED', 'login'));
        self::assert(!$acl->isAllowed('ADMIN', 'login'));
        self::assert($acl->isAllowed('ADMIN', 'admin_params'));
        self::assert(!$acl->isAllowed('LOGGED', 'admin_params'));
        self::assert(!$acl->isAllowed('PUBLIC', 'admin_params'));
        
        self::assert(!$acl->isAllowed('PUBLIC', 'info'));
        self::assert(!$acl->isAllowed('ADMIN', 'info'));
        self::assert($acl->isAllowed('admin@test.com', 'info'));
        self::assert($acl->isAllowed('otheradmin@test.com', 'info'));
        self::assert($acl->isAllowed('PUBLIC', 'info_public'));
        self::assert(!$acl->isAllowed('PUBLIC', 'info_private'));
        self::assert($acl->isAllowed('ADMIN', 'info_private'));
        self::assert($acl->isAllowed('reader@test.com', 'info_private'));
        self::assert($acl->isAllowed('reader@test.com', 'info_public'));
        
        self::assert(!$acl->isAllowed('reader@test.com', 'admin'));
        self::assert(!$acl->isAllowed('reader@test.com', 'admin_params'));
        self::assert(!$acl->isAllowed('LOGGED', 'admin'));
        self::assert(!$acl->isAllowed('PUBLIC', 'admin'));
        self::assert($acl->isAllowed('ADMIN', 'admin'));
        
        self::assert(!$acl->isAllowed('ADMIN', 'info'));
        self::assert(!$acl->isAllowed('PUBLIC', 'info'));
        self::assert(!$acl->isAllowed('LOGGED', 'info'));
        self::assert(!$acl->isAllowed('NOTLOGGED', 'info'));
        
        self::assert($acl->addRole('me@test.com', 'ADMIN'));
        self::assert(!$acl->isAllowed('me@test.com', 'info'));
        self::assert($acl->isAllowed('me@test.com', 'info_public'));
        self::assert($acl->isAllowed('me@test.com', 'info_private'));
        self::assert($acl->isAllowedParams('info', 'public', 'me@test.com'));
        self::assert($acl->isAllowedParams('info', 'private', 'me@test.com'));
        self::assert($acl->isAllowed('me@test.com', 'logout'));
        self::assert(!$acl->isAllowed('me@test.com', 'product'));

        self::assert($acl->isAllowedParams('info', 'public', 'me@test.com'));
        self::assert($acl->isAllowedParams('info', 'private', 'me@test.com'));
        self::assert(!$acl->isAllowedParams('product', null, 'me@test.com'));
        self::assert($acl->isAllowedParams('logout', null, 'me@test.com'));
        
        self::assert($acl->isAdmin('otheradmin@test.com'));
        self::assert(!$acl->isAdmin('ADMIN'));
        self::assert(!$acl->isAdmin('reader@test.com'));
        
        self::assertEqual($acl->buildResource('a', 'b'), 'a_b');
        self::assertEqual($acl->hasResourceParams('a', 'b'), false);
        self::assertEqual($acl->hasResourceParams('common', 'index'), true);
        self::assertEqual($acl->hasResourceParams('common'), true);
        
        self::assert($acl->inheritsRole('otheradmin@test.com', 'LOGGED'));
        self::assert($acl->inheritsRole('otheradmin@test.com', 'ADMIN'));
        self::assert(!$acl->inheritsRole('otheradmin@test.com', 'NOTLOGGED'));
        self::assert($acl->inheritsRole('ADMIN', 'LOGGED'));
        self::assert(!$acl->inheritsRole('ADMIN', 'NOTLOGGED'));
        
        return self::getResult();
    }
}
