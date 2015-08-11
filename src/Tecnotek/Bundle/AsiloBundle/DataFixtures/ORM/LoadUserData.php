<?php

namespace Tecnotek\Bundle\AsiloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tecnotek\Bundle\AsiloBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        //Create Roles
        $roleAdmin = new \Tecnotek\Bundle\AsiloBundle\Entity\Role();
        $roleAdmin->setName("Administrator");
        $roleAdmin->setRole("ROLE_ADMIN");
        $manager->persist($roleAdmin);

        $roleEmployee = new \Tecnotek\Bundle\AsiloBundle\Entity\Role();
        $roleEmployee->setName("Employee");
        $roleEmployee->setRole("ROLE_EMPLOYEE");
        $manager->persist($roleEmployee);

        $roleUser = new \Tecnotek\Bundle\AsiloBundle\Entity\Role();
        $roleUser->setName("User");
        $roleUser->setRole("ROLE_USER");
        $manager->persist($roleUser);

        /*** Create Users ***/
        $admin = new User();
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($admin);
        $admin->setUsername('administrador')->setPassword($encoder->encodePassword('p1l4rg4mb04', $admin->getSalt()))
            ->setName("Administrator")->setLastname("Site")->setCellPhone("");
        $admin->getUserRoles()->add($roleAdmin);
        $manager->persist($admin);

        /*** Create Sports ***/
        $this->createSports($manager);

        /*** Create Marital Status ***/
        $this->createMaritalStatus($manager);

        /*** Create Nutritional Status ***/
        $this->createNutriotionalStatus($manager);

        /*** Create Scholarities ***/
        $this->createScholarities($manager);

        /*** Create Pentions ***/
        $this->createPentions($manager);

        /*** Create all the general Cataloges ***/
        $this->createCatalogs($manager);
        $manager->flush();
    } // End of method: load

    public function createSports(ObjectManager $manager){
        $walk = new \Tecnotek\Bundle\AsiloBundle\Entity\Sport();
        $walk->setName("Caminata");
        $manager->persist($walk);

        $swing = new \Tecnotek\Bundle\AsiloBundle\Entity\Sport();
        $swing->setName("Natación");
        $manager->persist($swing);

        $relaxingExexcices = new \Tecnotek\Bundle\AsiloBundle\Entity\Sport();
        $relaxingExexcices->setName("Ejercicios de Relajación");
        $manager->persist($relaxingExexcices);
    }

    public function createMaritalStatus(ObjectManager $manager){
        $viudo = new \Tecnotek\Bundle\AsiloBundle\Entity\MaritalStatus();
        $viudo->setName("Viudo(a)");
        $manager->persist($viudo);

        $soltero = new \Tecnotek\Bundle\AsiloBundle\Entity\MaritalStatus();
        $soltero->setName("Soltero(a)");
        $manager->persist($soltero);

        $separado = new \Tecnotek\Bundle\AsiloBundle\Entity\MaritalStatus();
        $separado->setName("Separado(a)");
        $manager->persist($separado);

        $divorciado = new \Tecnotek\Bundle\AsiloBundle\Entity\MaritalStatus();
        $divorciado->setName("Divorciado(a)");
        $manager->persist($divorciado);

        $casado = new \Tecnotek\Bundle\AsiloBundle\Entity\MaritalStatus();
        $casado->setName("Casado(a)");
        $manager->persist($casado);
    }

    public function createNutriotionalStatus(ObjectManager $manager){
        $normal = new \Tecnotek\Bundle\AsiloBundle\Entity\NutritionalStatus();
        $normal->setName("Normal");
        $manager->persist($normal);

        $desnutricion = new \Tecnotek\Bundle\AsiloBundle\Entity\NutritionalStatus();
        $desnutricion->setName("Desnutrición");
        $manager->persist($desnutricion);

        $sobrepeso = new \Tecnotek\Bundle\AsiloBundle\Entity\NutritionalStatus();
        $sobrepeso->setName("Sobrepeso");
        $manager->persist($sobrepeso);

        $obesidad = new \Tecnotek\Bundle\AsiloBundle\Entity\NutritionalStatus();
        $obesidad->setName("Obesidad");
        $manager->persist($obesidad);
    }

    public function createScholarities(ObjectManager $manager){
        $primariaIncompleta = new \Tecnotek\Bundle\AsiloBundle\Entity\Scholarity();
        $primariaIncompleta->setName("Primaria incompleta");
        $manager->persist($primariaIncompleta);

        $primariaCompleta = new \Tecnotek\Bundle\AsiloBundle\Entity\Scholarity();
        $primariaCompleta->setName("Primaria completa");
        $manager->persist($primariaCompleta);

        $secundariaIncompleta = new \Tecnotek\Bundle\AsiloBundle\Entity\Scholarity();
        $secundariaIncompleta->setName("Secundaria incompleta");
        $manager->persist($secundariaIncompleta);

        $secundariaCompleta = new \Tecnotek\Bundle\AsiloBundle\Entity\Scholarity();
        $secundariaCompleta->setName("Secundaria Completa");
        $manager->persist($secundariaCompleta);

        $univsersitariaIncompleta = new \Tecnotek\Bundle\AsiloBundle\Entity\Scholarity();
        $univsersitariaIncompleta->setName("Universitaria incompleta");
        $manager->persist($univsersitariaIncompleta);

        $univsersitariaCompleta = new \Tecnotek\Bundle\AsiloBundle\Entity\Scholarity();
        $univsersitariaCompleta->setName("Universitaria Completa");
        $manager->persist($univsersitariaCompleta);
    }

    public function createPentions(ObjectManager $manager) {
        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("OTRA");
        $manager->persist($pention);

        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("CCSS IVM");
        $manager->persist($pention);

        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("CCSS MUERTE");
        $manager->persist($pention);

        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("CCSS RNC");
        $manager->persist($pention);

        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("CCSS VEJEZ");
        $manager->persist($pention);

        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("HACIENDA");
        $manager->persist($pention);

        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("MAGISTERIO");
        $manager->persist($pention);

        $pention = new \Tecnotek\Bundle\AsiloBundle\Entity\Pention();
        $pention->setName("MTSS");
        $manager->persist($pention);
    }

    public function createCatalogs(ObjectManager $manager)
    {
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Reading();
        $entity->setName("La Biblia");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Reading();
        $entity->setName("Periódico");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Reading();
        $entity->setName("Revistas");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Writing();
        $entity->setName("Vivencias");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Writing();
        $entity->setName("Oraciones");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Manuality();
        $entity->setName("Pintar");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Manuality();
        $entity->setName("Dibujar");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Music();
        $entity->setName("Popular");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Music();
        $entity->setName("Bolero");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Music();
        $entity->setName("70's");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Music();
        $entity->setName("80's");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Music();
        $entity->setName("Romántica");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Music();
        $entity->setName("Cristiana");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Instrument();
        $entity->setName("Guitarra");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Instrument();
        $entity->setName("Pianola");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Instrument();
        $entity->setName("Maracas");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\EntertainmentActivity();
        $entity->setName("Charlas");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\EntertainmentActivity();
        $entity->setName("Fiestas");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\EntertainmentActivity();
        $entity->setName("Cine Forum");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\EntertainmentActivity();
        $entity->setName("Talleres");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\RoomGame();
        $entity->setName("Papi Futbol");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\RoomGame();
        $entity->setName("Ajedrez");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\RoomGame();
        $entity->setName("Cartas");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\RoomGame();
        $entity->setName("Bingo");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Dance();
        $entity->setName("Bolero");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Dance();
        $entity->setName("Merengue");
        $manager->persist($entity);

    }
    } // End of Class