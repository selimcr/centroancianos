<?php
namespace Tecnotek\Bundle\AsiloBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

use Tecnotek\Bundle\AsiloBundle\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 *
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{

    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, g')
            ->leftJoin('u.roles', 'g')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(
                sprintf('Unable to find an active User object identified by "%s".', $username), 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * This method will return true or false depending if already exists
     * at least 1 record with the same email
     *
     * @param $email    Email to check
     *
     * @return bool     Exists or not
     */
    public function existsEmail($email)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('count(u)')
            ->from('TecnotekAsiloBundle:User', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery();

        $total = $query->getSingleScalarResult();

        return $total > 0;
    } // End of existsEmail

    public function getUsersByRole($role)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, r')
            ->leftJoin('u.roles', 'r')
            ->where('r.role = :role')
            ->setParameter('role', $role)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $users = $q->getResult();
        } catch (NoResultException $e) {
        }

        return $users;
    }
}

?>
