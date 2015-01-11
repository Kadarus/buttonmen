<?php
/**
 * BMSkillTrip: Code specific to the trip die skill
 *
 * @author james
 */

/**
 * This class contains code specific to the trip die skill
 */
class BMSkillTrip extends BMSkill {
    /**
     * An array containing the names of functions run by
     * BMCanHaveSkill->run_hooks()
     *
     * @var array
     */
    public static $hooked_methods = array('attack_list',
                                          'initiative_value',
                                          'capture');

    /**
     * Hooked method applied when determining possible attack types
     *
     * @param array $args
     */
    public static function attack_list($args) {
        if (!is_array($args)) {
            return;
        }

        $attackTypeArray = &$args['attackTypeArray'];
        $attackTypeArray['Trip'] = 'Trip';
    }

    /**
     * Hooked method applied when determining the initiative value of a die
     *
     * @param array $args
     */
    public static function initiative_value(&$args) {
        if (!is_array($args)) {
            return;
        }

        // trip dice don't contribute to initiative
        $args['initiativeValue'] = -1;
    }

    /**
     * Hooked method applied during capture
     *
     * @param array $args
     */
    public static function capture(&$args) {
        if ($args['type'] != 'Trip') {
            return;
        }

        assert(1 == count($args['attackers']));
        assert(1 == count($args['defenders']));

        $attacker = &$args['attackers'][0];
        $attacker->roll(TRUE);
        $attacker->add_flag('JustPerformedTripAttack', $attacker->value);

        $defender = &$args['defenders'][0];
        $defender->roll(TRUE);

        $defender->captured = ($defender->value <= $attacker->value);
        if (!$defender->captured) {
            $defender->remove_flag('WasJustCaptured');
            $attacker->add_flag('JustPerformedUnsuccessfulAttack');
        }
    }

    /**
     * Description of skill
     *
     * @return string
     */
    protected static function get_description() {
        return 'These dice can also make Trip Attacks. To make a Trip Attack, choose any one opposing die as the ' .
               'Target. Roll both the Trip Die and the Target, then compare the numbers they show. If the Trip Die ' .
               'now shows an equal or greater number than the Target, the Target is captured. Otherwise, the attack ' .
               'merely has the effect of re-rolling both dice. A Trip Attack is illegal if it has no chance of ' .
               'capturing (this is possible in the case of a Trip-1 attacking a Twin Die). IMPORTANT: Trip Dice do ' .
               'not count for determining who goes first.';
    }

    /**
     * Descriptions of interactions between this skill and other skills
     *
     * An array, indexed by other skill name, whose values are descriptions of
     * interactions between the relevant skills
     *
     * @return array
     */
    protected static function get_interaction_descriptions() {
        return array(
            'Queer' => 'Dice with both Queer and Trip skills always determine their success or failure at Trip ' .
                       'Attacking via a Power Attack',
            'Shadow' => 'Dice with both Shadow and Trip skills always determine their success or failure at Trip ' .
                        'Attacking via a Power Attack',
        );
    }
}
