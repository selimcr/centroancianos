<?php
namespace Tecnotek\Bundle\AsiloBundle\Util\Enum;

/**
 *
 */
abstract class PermissionsEnum extends BasicEnum {
    const EDIT_PATIENT = 1;
    const EDIT_PATIENT_ACTIVITIES = 2;
    const EDIT_PATIENT_ACTIVITY_COGNITIVE = 3;
    const EDIT_PATIENT_ACTIVITY_RECREATIONAL = 4;
    const EDIT_PATIENT_ACTIVITY_SPIRITUALS = 5;
    const EDIT_PATIENT_ACTIVITY_BASIC_NEEDS = 6;
    const EDIT_PATIENT_ACTIVITY_BIOLOGIC = 7;
    const EDIT_PATIENT_ACTIVITY_PSICOLOGIC = 8;
}
?>
