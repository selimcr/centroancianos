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
        $walk = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Sport();
        $walk->setName("Caminata");
        $manager->persist($walk);
        $swing = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Sport();
        $swing->setName("Natación");
        $manager->persist($swing);
        $relaxingExexcices = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Sport();
        $relaxingExexcices->setName("Ejercicios de Relajación");
        $manager->persist($relaxingExexcices);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Reading();
        $entity->setName("La Biblia");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Reading();
        $entity->setName("Periódico");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Reading();
        $entity->setName("Revistas");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Writing();
        $entity->setName("Vivencias");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Writing();
        $entity->setName("Oraciones");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Manuality();
        $entity->setName("Pintar");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Manuality();
        $entity->setName("Dibujar");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Music();
        $entity->setName("Popular");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Music();
        $entity->setName("Bolero");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Music();
        $entity->setName("70's");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Music();
        $entity->setName("80's");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Music();
        $entity->setName("Romántica");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Music();
        $entity->setName("Cristiana");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Instrument();
        $entity->setName("Guitarra");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Instrument();
        $entity->setName("Pianola");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Instrument();
        $entity->setName("Maracas");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\EntertainmentActivity();
        $entity->setName("Charlas");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\EntertainmentActivity();
        $entity->setName("Fiestas");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\EntertainmentActivity();
        $entity->setName("Cine Forum");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\EntertainmentActivity();
        $entity->setName("Talleres");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\RoomGame();
        $entity->setName("Papi Futbol");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\RoomGame();
        $entity->setName("Ajedrez");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\RoomGame();
        $entity->setName("Cartas");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\RoomGame();
        $entity->setName("Bingo");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Dance();
        $entity->setName("Bolero");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Dance();
        $entity->setName("Merengue");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Religion();
        $entity->setName("Católica");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Religion();
        $entity->setName("Evangélica");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Religion();
        $entity->setName("Protestante");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity();
        $entity->setName("Ayudar a los demás");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity();
        $entity->setName("Rezar el rosario");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity();
        $entity->setName("Enseñar la Biblia");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity();
        $entity->setName("Encomendarce al Señor");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity();
        $entity->setName("Culto");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity();
        $entity->setName("Hora Santa");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity();
        $entity->setName("Santa Misa");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SleepHabit();
        $entity->setName("Suele levantarse");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SleepHabit();
        $entity->setName("Necesita ir al baño varias veces");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SleepHabit();
        $entity->setName("Durante el día duerme mucho");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SleepHabit();
        $entity->setName("Necesita dormir con la luz encendida");
        $manager->persist($entity);

        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Alcoholismo");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Cáncer");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Cardiopatía");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Cirrosis");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Cáncer de Seno");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Accidente Vascular Periférico");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Alzheimer");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Enfermedad Renal");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Sarampión");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Paperas");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Diabetes Mellitus");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Hipotiroidismo");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Hipertensión Arterial");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Diverticulitis");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Ulcera gastrica");
        $manager->persist($entity);
        $entity = new \Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Disease();
        $entity->setName("Cáncer de Próstata");
        $manager->persist($entity);



    }
} // End of Class